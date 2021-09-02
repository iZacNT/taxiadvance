<?php

namespace backend\controllers;

use backend\models\Driver;
use backend\models\Settings;
use backend\models\DriverSearch;
use common\models\User;
use common\service\driver\DriverDebt;
use common\service\driver\DriverDeposit;
use common\service\driver\DriverParams;
use common\service\driver\PrepareDriverService;
use common\service\driver\PrepareOrdersService;
use common\service\driver\PrepareTransactionService;
use common\service\user\UserService;
use common\service\yandex\params\ParamsSearchAllOrdersDriver;
use common\service\yandex\params\ParamsSearchDriver;
use common\service\yandex\YandexApi;
use common\service\yandex\YandexService;
use Throwable;
use Yii;
use yii\db\StaleObjectException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * DriverController implements the CRUD actions for Driver model.
 */
class DriverController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors(): array
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Driver models.
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new DriverSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Driver model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(int $id): string
    {
        $driver = $this->findModel($id);

        $driverDeposit = new DriverDeposit($id);
        $depositDataProvider = $driverDeposit->getAllDeposits();
        $summDeposit = $driverDeposit->getSummDeposit();

        $debtDriver = new DriverDebt($id);
        $debtDataProvider = $debtDriver->getAllDebt();
        $summDebt = $debtDriver->getSummDebt();

        $depo = Yii::$app->formatter->asCurrency((new PrepareDriverService())->getDepoSumm(
            $summDeposit,
            $summDebt,
            0,
            5000,
            300,
            100));

        $settings = Settings::find()->one();

        $serviceYandex = new YandexService($driver, $settings);
        $allDriverOrders = $serviceYandex->getAllOrders();

        if (!empty($allDriverOrders)){
            $servicePrepare = new PrepareOrdersService($allDriverOrders['orders']);
            $preparedOrders = $servicePrepare->prepareOrders();
            $summOrders = $servicePrepare->summOrders();
        }

        $balanceDriverYandex = Yii::$app->formatter->asCurrency($serviceYandex->getBalanceFromYandex());
        $allTransactions = $serviceYandex->getDriverTransaction();
        if (!empty($allTransactions)){
            $servicePrepareTransactions = new PrepareTransactionService($allTransactions['transactions']);
            $bonus = Yii::$app->formatter->asCurrency($servicePrepareTransactions->getBonusDriver());
        }

        return $this->render('view', [
            'model' => $driver,
            'depositDataProvider' => $depositDataProvider,
            'summDeposit' => $summDeposit,
            'debtDataProvider' => $debtDataProvider,
            'summDebt' => $summDebt,
            'allOreders' => $preparedOrders,
            'summOrders' => $summOrders,
            'balanceYandex' => $balanceDriverYandex,
            'bonus' => $bonus,
            'depo' => $depo
        ]);
    }

    /**
     * Creates a new Driver model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate()
    {
        $driver = new Driver();

        if ($this->request->isPost) {
            if ($driver->load($this->request->post()) && $driver->validate()) {
                $paramsDriver = (new DriverParams($driver))->generateParams();
                $user = (new UserService)->create(new User(), $paramsDriver);
                $driver->shift_closing = strtotime($driver->stringShiftClosing);
                $driver->user_id = $user->id;
                $driver->save();

                return $this->redirect(['view', 'id' => $driver->id]);
            }
        } else {
            $driver->loadDefaultValues();
        }
        $driver->stringShiftClosing = Yii::$app->formatter->asDatetime(time(), "yyyy-MM-dd HH:mm");

        return $this->render('create', [
            'model' => $driver,
        ]);
    }

    /**
     * Updates an existing Driver model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return Response|string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post())) {

            $model->shift_closing = strtotime($model->stringShiftClosing);
            $model->save();

            return $this->redirect(['view', 'id' => $model->id]);
        }

        (!empty($model->shift_closing)) ? $shiftClosing = $model->shift_closing : $shiftClosing = time();
        $model->stringShiftClosing = Yii::$app->formatter->asDatetime($shiftClosing, "yyyy-MM-dd HH:mm");

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Driver model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionDelete(int $id): Response
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Driver model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Driver the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): Driver
    {
        if (($model = Driver::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @return false|string
     */

    public function actionSearchDriverInYandex()
    {
        $driver_license = Yii::$app->request->post("driver_license");
        $settings = Settings::find()->one();
        $params = new ParamsSearchDriver(
            $settings->yandex_client_id,
            $driver_license

        );


        $req = new YandexApi(
            'https://fleet-api.taxi.yandex.net/v1/parks/driver-profiles/list',
            $settings->yandex_client_id,
            $settings->yandex_api,
            $params
        );
        return json_encode($req->request());
    }

}
