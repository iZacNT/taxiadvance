<?php

namespace backend\controllers;

use backend\models\DayPlans;
use backend\models\DriverBilling;
use backend\models\DriverTabel;
use backend\models\Cars;
use backend\models\Driver;
use backend\models\Settings;
use backend\models\DriverSearch;
use common\models\User;
use common\service\constants\Constants;
use common\service\driver\BillingDeleteService;
use common\service\driver\CalculationShiftParams;
use common\service\driver\DriverAllShiftsService;
use common\service\driver\DriverBillingService;
use common\service\driver\DriverDebt;
use common\service\driver\DriverDeposit;
use common\service\driver\DriverParams;
use common\service\driver\PrepareDriverService;
use common\service\driver\PrepareOrdersService;
use common\service\driver\PrepareRangeShift;
use common\service\driver\PrepareTransactionService;
use common\service\user\UserService;
use common\service\yandex\params\ParamsSearchDriver;
use common\service\yandex\YandexApi;
use common\service\yandex\YandexService;
use phpDocumentor\Reflection\Types\Boolean;
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
        $settings = Settings::find()->one();
        $carsOnWork = Cars::prepareCarsForAutocomplete(Yii::$app->formatter->asBeginDay(time()));
        $driverDeposit = new DriverDeposit($id);
        $depositDataProvider = $driverDeposit->getAllDeposits();
        $summDeposit = $driverDeposit->getSummDeposit();
        $debtDriver = new DriverDebt($id);
        $debtDataProvider = $debtDriver->getAllDebt();
        $summDebt = $debtDriver->getSummDebt();

        $allShiftsDriverService = new DriverAllShiftsService($id);
        $allShiftDataProvider = $allShiftsDriverService->getAllShiftsDataProvider();

        $prepareDriverService = new PrepareDriverService($id);
        $depo = ($prepareDriverService)->getDepoSumm(
            $summDeposit,
            $summDebt,
            $settings->depo_min,
            $settings->depo_max,
            $settings->les_summ,
            $settings->more_summ);

        $serviceYandex = new YandexService($driver, $settings);
        $allDriverOrders = $serviceYandex->getAllOrders();

        if (!empty($allDriverOrders)){
            $servicePrepare = new PrepareOrdersService($allDriverOrders['orders']);
            $preparedOrders = $servicePrepare->prepareOrders();
            $summOrders = $servicePrepare->summOrders();
        }else{
            Yii::$app->session->setFlash("error", "???? ?????????????? ???????????????? ???????????? ????????????????. ???????????????? ????????????????!");
        }

        $balanceDriverYandex = round($serviceYandex->getBalanceFromYandex());
        $allTransactions = $serviceYandex->getDriverTransaction();

        $amountTransactionByAllType = "";
        $amountTableTransaction = "";
        if (!empty($allTransactions)){
            $servicePrepareTransactions = new PrepareTransactionService($allTransactions['transactions']);
//            $bonus = $servicePrepareTransactions->getBonusDriver();
            $bonus = $servicePrepareTransactions->getSumOfTransactionByType();
            $amountTransactionByAllType = $servicePrepareTransactions->prepareHtmlTransactionByType();
            $amountTableTransaction = $servicePrepareTransactions->prepareTableTransaction();
        }else{
            $bonus = 0;
            Yii::$app->session->setFlash("error", "???? ?????????????? ???????????????? ???????????????????? ????????????????. ???????????????? ????????????????!");
        }

        $driverTabelProviderAll = $prepareDriverService->getDriverTabelArray();
        $currentShift = $prepareDriverService->getCurrentShiftFromArray();
        $shiftID = $currentShift['id_shift'];
        $period = $currentShift['period'];
        $carFuel = $currentShift['car_fuel'];
        $carFuelLabel = $currentShift['car_fuel_label'];
        $numberPhoneCard = $prepareDriverService->getNumberCardPhone($currentShift);
        $carId = $currentShift['car_id'];
        $hours = $prepareDriverService->getCountHoursFromOrders($allDriverOrders['orders']);

        $dayPlan = (new DayPlans())->getPlan($driver->filial, $period , Constants::WORKING_DAY, $hours);
        \Yii::debug($dayPlan);
        $carsMarks = (new Cars())->getAllMarks();
        $generateTarifTable = $prepareDriverService->generateTarifTable(2, $period,$carFuel, $hours, $carsMarks, $currentShift);

        return $this->render('view', [
            'model' => $driver,
            'depositDataProvider' => $depositDataProvider,
            'summDeposit' => $summDeposit, // ?????????? ?????????????????? ????????????????
            'debtDataProvider' => $debtDataProvider,
            'allShiftDataProvider' => $allShiftDataProvider,
            'driverTabelProviderAll' => $driverTabelProviderAll, //?????? ?????????? ???????????????? ?? ????????????
            'summDebt' => $summDebt, // ?????????? ???????????? ????????????????
            'allOreders' => $preparedOrders, //?????? ???????????? ???????????????? ?? ?????????????? ???????????????? ??????????
            'summOrders' => $summOrders, //?????????? ?????????????? ????????????????
            'balanceYandex' => $balanceDriverYandex, // ???????????? ?? ????????????
            'bonus' => $bonus, //???????????? ?? ????????????
            'depo' => $depo, //????????
            'plan' => $dayPlan, // ????????
            'currentShift' => $currentShift,
            'shiftID' => $shiftID,
            'carFuel' => $carFuelLabel, //?????????????? ?????????????????????????? ????????????????????
            'car' => $currentShift['car_full_name'], //?????????? ???????????? ????????
            'car_id' => $carId, // Car ID
            'card' => $numberPhoneCard['card'], //???????? ???? ??????????
            'sum_card' => $numberPhoneCard['sum_card'], //?????????? ???????????? ???? ????????????
            'phone' => $numberPhoneCard['phone'], // ???????? ???? ??????????????
            'sum_phone' => $numberPhoneCard['sum_phone'], // ?????????? ???????????? ???? ??????????????
            'generateTarifTable' => $generateTarifTable,
            'amountTransactionByAllType' => $amountTransactionByAllType,
            'amountTableTransaction' => $amountTableTransaction,
            'carsOnWork' => $carsOnWork //?????? ???????????????????? ???????????? ?????? Autocomplete
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
            if ($driver->load($this->request->post())) {
                $paramsDriver = new DriverParams($driver);
                $params = $paramsDriver->generateParams();
                $userService = new UserService;
                $user = $userService->searchUserByUsername($params['username']);
                if (!$user){
                    $user = $userService->create(new User(), $params);
                    $user->refresh();
                    $paramsDriver->createDriver($driver, $user);
                    $driver->refresh();
                    return $this->redirect(['view', 'id' => $driver->id]);
                }else{
                    if ($paramsDriver->searchDriverByIdUser($user->id)){
                        Yii::$app->session->setFlash('error', '???????????????? ?????? ????????????????????!');
                        return $this->redirect(['create']);
                    }else{
                        $paramsDriver->createDriver($driver, $user);
                        $driver->refresh();
                        return $this->redirect(['view', 'id' => $driver->id]);
                    }
                }
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
            $model->birth_date = strtotime($model->stringBirthDay);
            $model->save();

            return $this->redirect(['view', 'id' => $model->id]);
        }

        (!empty($model->shift_closing)) ? $shiftClosing = $model->shift_closing : $shiftClosing = time();
        $model->stringShiftClosing = date("Y-m-d H:i", $shiftClosing);
        $model->stringBirthDay = (!empty($model->birth_date)) ? Yii::$app->formatter->asDate($model->birth_date, "yyyy-MM-dd"): '';

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

    public function actionCalculateShift()
    {

        $requestPost = Yii::$app->request->post();
        Yii::debug($requestPost, __METHOD__);

        $plan = (new DayPlans())->getPlan($requestPost['filial'], $requestPost['period'] , $requestPost['typeDay'], $requestPost['hours']);
        $calculationShiftParams = new CalculationShiftParams($requestPost, $plan);
        $billing = $calculationShiftParams->getGeneralAmount();

        return json_encode($billing);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function actionSaveBilling()
    {
        $billingService = new DriverBillingService(Yii::$app->request->post());
        $res = $billingService->saveAmount();
        Yii::debug($res, __METHOD__);
        return json_encode($res);
    }

    public function actionVerifyBilling(): bool
    {
        $user_id = Yii::$app->request->post("user_id");
        $model_id = Yii::$app->request->post("model_id");

        $model = DriverBilling::find()->where(['id' => $model_id])->one();
        $model->verify = $user_id;
        $model->save();

        return true;
    }

    public function actionVerifyRangeShift(): string
    {
        Yii::debug(Yii::$app->request->post(),__METHOD__);
        return (new PrepareRangeShift())->prepareRangeShifts(Yii::$app->request->post());
    }

    public function actionSaveRangeShift()
    {
        return json_encode((new PrepareRangeShift())->saveRange(Yii::$app->request->post()));
    }
}
