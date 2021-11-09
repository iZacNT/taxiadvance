<?php


namespace backend\controllers;


use app\models\DriverTabel;
use app\models\DriverTabelSearch;
use backend\models\Cars;
use backend\models\Driver;
use backend\models\DriverTTabelSearch;
use backend\models\Phones;
use backend\models\Settings;
use common\service\driverTabel\PrepareDriverTabel;
use common\service\driverTabel\StatistycDriverTabel;
use frontend\models\ContactForm;
use Throwable;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class DriverTabelController extends Controller
{
    public function actionIndex()
    {
        $searchModel = new DriverTabelSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $drivers = Driver::prepareDriversForAutocomplete();

        $dateSearchFrom = (Yii::$app->formatter->asBeginDay(time()))-(24*60*60);
        if (Yii::$app->request->get("dateSearchFrom")){
            $dateSearchFrom = strtotime(Yii::$app->request->get("dateSearchFrom"));

        }
        Yii::debug("Дата/Время начала поиска водителей в Табеле ".$dateSearchFrom." ".Yii::$app->formatter->asDatetime($dateSearchFrom) , __METHOD__);

        $prepareService = new PrepareDriverTabel($dateSearchFrom);
        $columns = $prepareService->generateColumns($dateSearchFrom);
        $columnsDay = $prepareService->generateColumnsDay($dateSearchFrom);
        $columnsNight = $prepareService->generateColumnsNight($dateSearchFrom);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'columns' => $columns,
            'columnsDay' => $columnsDay,
            'columnsNight' => $columnsNight,
            'drivers' => $drivers
        ]);
    }

    public function actionCreate()
    {
        $driverTabel = new DriverTabel();
        if (\Yii::$app->request->get("workDate")){
            $driverTabel->work_date = Yii::$app->formatter->asDate(\Yii::$app->request->get("workDate"),"yyyy-MM-dd");

        }else{
            $driverTabel->work_date = Yii::$app->formatter->asDate(time(),"yyyy-MM-dd");
        }

        $beginDate = \Yii::$app->formatter->asBeginDay(strtotime($driverTabel->work_date));
        $endDate = \Yii::$app->formatter->asEndDay(strtotime($driverTabel->work_date));
        $workDrivers = $this->getWorksDriversAtDay($beginDate, $endDate);
        $busyPhones = $this->getBusyPhones($beginDate, $endDate);

        $cars = Cars::prepareCarsForAutocomplete($driverTabel->work_date,\Yii::$app->user->identity->getFilialUser());

        $drivers = Driver::find()
            ->select(['concat(last_name, " ", first_name) as value', 'concat(last_name, " ", first_name) as  label','id as id'])
            ->where(['not in','id', $workDrivers])
            ->asArray()
            ->all();

        $freePhones = Phones::preparePhonesForAutocomplete($busyPhones);

        $phone_sum = (Settings::find()->select('phone_sum')->one())->phone_sum;

        if ($this->request->isPost) {
            if ($driverTabel->load($this->request->post())) {
                $driverTabel->work_date = strtotime($driverTabel->work_date);
                if ($driverTabel->phone_day){
                    $driverTabel->sum_phone_day = $phone_sum;
                }

                if ($driverTabel->phone_night){
                    $driverTabel->sum_phone_night = $phone_sum;
                }

                $driverTabel->save();

                return $this->redirect(['view', 'id' => $driverTabel->id]);
            }
        } else {
            $driverTabel->loadDefaultValues();
        }

        if (\Yii::$app->request->get("carId")){
            $driverTabel->car_id = \Yii::$app->request->get("carId");
            foreach($cars as $car){
                if ($car['id'] == \Yii::$app->request->get("carId")){
                    $driverTabel->stringNameCar = $car['label'];
                }
            }
        }

        return $this->render('create', [
            'driverTabel' => $driverTabel,
            'cars' => $cars,
            'drivers' => $drivers,
            'freePhones' => $freePhones
        ]);
    }

    public function actionUpdate(int $id)
    {
        $driverTabel = $this->findModel($id);
        $oldDate = $driverTabel->work_date;

        $beginDate = \Yii::$app->formatter->asBeginDay(strtotime($driverTabel->work_date));
        $endDate = \Yii::$app->formatter->asEndDay(strtotime($driverTabel->work_date));
        $busyPhones = $this->getBusyPhones($beginDate, $endDate);

        $cars = Cars::prepareCarsForAutocomplete($driverTabel->work_date,\Yii::$app->user->identity->getFilialUser());
        $drivers = Driver::find()
            ->select(['concat(last_name, " ", first_name) as value', 'concat(last_name, " ", first_name) as  label','id as id'])
            ->asArray()
            ->all();
        $phone_sum = (Settings::find()->select('phone_sum')->one())->phone_sum;

        $freePhones = Phones::preparePhonesForAutocomplete($busyPhones);

        if ($this->request->isPost && $driverTabel->load($this->request->post())) {
            $formattedDate = strtotime($driverTabel->work_date);
            if ($driverTabel->isValidDay($formattedDate, $driverTabel->car_id, $oldDate)){
                $driverTabel->work_date = $formattedDate;
                if ($driverTabel->phone_day){
                    $driverTabel->sum_phone_day = $phone_sum;
                }else{
                    $driverTabel->sum_phone_day = '';
                }

                if ($driverTabel->phone_night){
                    $driverTabel->sum_phone_night = $phone_sum;
                }else{
                    $driverTabel->sum_phone_night = '';
                }

                $driverTabel->save();
            }else{
                Yii::$app->session->setFlash("error", 'На дату <strong>'.$driverTabel->work_date.'</strong> для Автомобиля <strong>'.$driverTabel->stringNameCar.'</strong> уже назначены водители!');
                return $this->redirect(['update', 'id' => $driverTabel->id]);
            }
            return $this->redirect(['view', 'id' => $driverTabel->id]);
        }

        $driverTabel->stringDriverDay = (empty($driverTabel->fullDayDriverName->fullName))? "" : $driverTabel->fullDayDriverName->fullName ;
        $driverTabel->stringDriverNight = (empty($driverTabel->fullNightDriverName->fullName))? "" : $driverTabel->fullNightDriverName->fullName;
        foreach($cars as $car){
            if ($car['id'] == $driverTabel->car_id){
                $driverTabel->stringNameCar = $car['label'];
            }
        }

        return $this->render('update', [
            'driverTabel' => $driverTabel,
            'cars' => $cars,
            'drivers' => $drivers,
            'freePhones' => $freePhones
        ]);
    }

    /**
     * Finds the Driver model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return DriverTabel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): DriverTabel
    {
        if (($driverTabel = DriverTabel::findOne($id)) !== null) {
            return $driverTabel;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
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

    public function actionView($id)
    {
        return $this->render('view', [
            'driverTabel' => $this->findModel($id),
        ]);
    }

    public function actionSearchDriverByDateAndFio()
    {
        $response = [
            'full_name' => '-',
            'phone' => 0,
            'card' => 0,
            'status_shift' => '-',
            'close_shift' => '-',
            'length' => 0
        ];
        $requestData = Yii::$app->request->post();
        $date = $requestData['date'];
        $driver_id = $requestData['driver_id'];
        $beginDate = \Yii::$app->formatter->asBeginDay($date);
        $endDate = \Yii::$app->formatter->asEndDay($date);
        $driverData = DriverTabel::find()
            ->where(['driver_id_day' => $requestData["driver_id"]])
            ->orWhere(['driver_id_night' => $requestData["driver_id"]])
            ->andWhere(['>=', 'work_date', $beginDate])
            ->andWhere(['<=', 'work_date', $endDate])
            ->one();
        if ($driverData){
            $response = [
                'id' => $driver_id,
                'car' => $driverData->carInfo->fullNameMark,
                'full_name' => ($driver_id == $driverData->driver_id_day)? $driverData->fullDayDriverName->fullName : $driverData->fullNightDriverName->fullname,
                'phone' => ($driver_id == $driverData->driver_id_day) ?
                    (empty($driverData->phone_day)) ? "Нет" : $driverData->phone_day
                    :
                    (empty($driverData->phone_night))? "Нет" : $driverData->phone_night,
                'card' => ($driver_id == $driverData->driver_id_day) ?
                    (empty($driverData->sum_card_day)) ? Yii::$app->formatter->asCurrency(0): Yii::$app->formatter->asCurrency($driverData->sum_card_day)
                    :
                    (empty($driverData->sum_card_night)) ? Yii::$app->formatter->asCurrency(0): Yii::$app->formatter->asCurrency($driverData->sum_card_night) ,
                'shift' => ($driver_id == $driverData->driver_id_day)? "Дневная" : "Ночная",
                'status_shift' => ($driver_id == $driverData->driver_id_day) ?
                    $driverData::labelStatusShift()[$driverData->status_day_shift]
                    :
                    $driverData::labelStatusShift()[$driverData->status_night_shift],
                'close_shift' => ($driver_id == $driverData->driver_id_day) ?
                    (empty($driverData->date_close_day_shift)) ? "-" : Yii::$app->formatter->asDatetime($driverData->date_close_day_shift)
                    :
                    (empty($driverData->date_close_night_shift)) ? "-" : Yii::$app->formatter->asDatetime($driverData->date_close_night_shift),

                'length' => 1
                ];
        }

        return json_encode($response);
    }

    private function getWorksDriversAtDay(int $beginDate, int $endDate): array
    {
        $qDay = DriverTabel::find()->select('driver_id_day as id_driver')->where(['>=', 'work_date', $beginDate])
            ->andWhere(['<=', 'work_date', $endDate])->asArray()->all();
        $qNight = DriverTabel::find()->select('driver_id_night as id_driver')->where(['>=', 'work_date', $beginDate])
            ->andWhere(['<=', 'work_date', $endDate])->asArray()->all();
        $workDrivers = [];
        foreach ($qDay as $item){
            array_push($workDrivers, $item['id_driver']);
        }
        foreach ($qNight as $item){
            array_push($workDrivers, $item['id_driver']);
        }
        Yii::debug($workDrivers, __METHOD__);
        return $workDrivers;
    }

    private function getBusyPhones($beginDate, $endDate): array
    {
        $bPhones = DriverTabel::find()
            ->select('id')
            ->where(['>=', 'work_date', $beginDate])
            ->andWhere(['<=', 'work_date', $endDate])
            ->asArray()
            ->all();
        $phones = [];
        foreach ($bPhones as $item){
            array_push($phones, $item['id']);
        }
        return $phones;
    }

    public function actionGenerateStatisticByDay()
    {
        $dateStat = Yii::$app->request->post("date");
        return json_encode((new StatistycDriverTabel())->generateStatisticByDay($dateStat));
    }
}