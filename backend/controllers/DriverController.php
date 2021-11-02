<?php

namespace backend\controllers;

use app\models\DayPlans;
use app\models\DriverTabel;
use backend\models\Cars;
use backend\models\Driver;
use backend\models\Settings;
use backend\models\DriverSearch;
use common\models\User;
use common\service\constants\Constants;
use common\service\driver\CalculationShiftParams;
use common\service\driver\DriverAllShiftsService;
use common\service\driver\DriverBillingService;
use common\service\driver\DriverDebt;
use common\service\driver\DriverDeposit;
use common\service\driver\DriverParams;
use common\service\driver\PrepareDriverService;
use common\service\driver\PrepareOrdersService;
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
            Yii::$app->session->setFlash("error", "Не удалось получить заказы Водителя. Обновите страницу!");
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
            Yii::$app->session->setFlash("error", "Не удалось получить транзакции Водителя. Обновите страницу!");
        }

        $driverTabelProviderAll = $prepareDriverService->getDriverTabel();
        //$currentShift = $prepareDriverService->getCurrentShift();

        $currentShift = $prepareDriverService->getCurrentShiftFromArray();

        //$shiftID = $prepareDriverService->getCurrentShiftID($currentShift);
        $shiftID = $currentShift['id_shift'];

        //$period = $prepareDriverService->getPeriodShift($currentShift);
        $period = $currentShift['period'];

//        $carFuel = $prepareDriverService->getCarFuel($currentShift);
        $carFuel = $currentShift['car_fuel'];

//        $carFuelLabel = Constants::getFuel()[$carFuel];
        $carFuelLabel = $currentShift['car_fuel_label'];

        $numberPhoneCard = $prepareDriverService->getNumberCardPhone($period, $currentShift);
        \Yii::debug($numberPhoneCard);

        $carId = $currentShift['car_id'];

        $hours = $prepareDriverService->getCountHoursFromOrders($allDriverOrders['orders']);
//        $carInfo = $prepareDriverService->getCarInfo($currentShift);

        $dayPlan = (new DayPlans())->getPlan($driver->filial, $period , Constants::WORKING_DAY, $hours);
        \Yii::debug($dayPlan);
        $carsMarks = (new Cars())->getAllMarks();
        $generateTarifTable = $prepareDriverService->generateTarifTable(2, $period,$carFuel, $hours, $carsMarks, $currentShift);



        return $this->render('view', [
            'model' => $driver,
            'depositDataProvider' => $depositDataProvider,
            'summDeposit' => $summDeposit, // Сумма депозитов Водителя
            'debtDataProvider' => $debtDataProvider,
            'allShiftDataProvider' => $allShiftDataProvider,
            'driverTabelProviderAll' => $driverTabelProviderAll, //Все смены водителя в табеле
            'summDebt' => $summDebt, // Сумма Долгов Водителя
            'allOreders' => $preparedOrders, //Все Заказы Водителя с момента закрытия смены
            'summOrders' => $summOrders, //Сумма заказов Водителя
            'balanceYandex' => $balanceDriverYandex, // Баланс в Яндекс
            'bonus' => $bonus, //Бонусы в Яндекс
            'depo' => $depo, //Депо
            'plan' => $dayPlan, // План
            'currentShift' => $currentShift,
            'shiftID' => $shiftID,
            'carFuel' => $carFuelLabel, //Топливо используемого автомобиля
            'car' => $currentShift['car_full_name'], //Марка модель Авто
            'car_id' => $carId, // Car ID
            'card' => $numberPhoneCard['card'], //Брал ли карту
            'sum_card' => $numberPhoneCard['sum_card'], //Сумма взятая на бензин
            'phone' => $numberPhoneCard['phone'], // Брал ли телефон
            'sum_phone' => $numberPhoneCard['sum_phone'], // Сумма взятая на телефон
            'generateTarifTable' => $generateTarifTable,
            'amountTransactionByAllType' => $amountTransactionByAllType,
            'amountTableTransaction' => $amountTableTransaction
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
                        Yii::$app->session->setFlash('error', 'Водитель уже существует!');
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

    public function actionCalculateShift()
    {

        $requestPost = Yii::$app->request->post();

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
        return json_encode($billingService->saveAmount());
    }

}
