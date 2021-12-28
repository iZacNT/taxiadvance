<?php

namespace backend\controllers;

use backend\models\DriverTabel;
use backend\models\Cars;
use backend\models\DebtFines;
use backend\models\DebtFinesSearch;
use backend\models\Driver;
use common\service\driver\DriverDebt;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DebtFinesController implements the CRUD actions for DebtFines model.
 */
class DebtFinesController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
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
     * Lists all DebtFines models.
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModel = new DebtFinesSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DebtFines model.
     * @param int $id #
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'debtFines' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new DebtFines model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $debtFines = new DebtFines();
        $cars = Cars::prepareCarsForAutocomplete(\Yii::$app->user->identity->getFilialUser());
        $drivers = Driver::prepareDriversForAutocomplete();
        if ($this->request->isPost) {
            if ($debtFines->load($this->request->post())) {

                (new DriverDebt($debtFines->driver_id))->createDebt($debtFines);

                return $this->redirect(['view', 'id' => $debtFines->id]);
            }
        } else {
            $debtFines->loadDefaultValues();
        }
        $debtFines->reason = 1;

        return $this->render('create', [
            'debtFines' => $debtFines,
            'drivers' => $drivers,
            'cars' => $cars,
        ]);
    }

    /**
     * Updates an existing DebtFines model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id #
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $debtFines = $this->findModel($id);
        $cars = Cars::prepareCarsForAutocomplete(\Yii::$app->user->identity->getFilialUser());
        $drivers = Driver::prepareDriversForAutocomplete();

        if ($this->request->isPost && $debtFines->load($this->request->post())) {

            try {
                (new DriverDebt($debtFines->driver_id))->updateDebt($debtFines);
            } catch (\Exception $exception){
                throw new \DomainException($exception->getMessage());
            }

            return $this->redirect(['view', 'id' => $debtFines->id]);
        }

        $debtFines->stringDateReason = date('Y-m-d', $debtFines->date_reason);

        $debtFines->stringDateDtp = (!empty($debtFines->date_dtp))? date('d-m-Y h:i', $debtFines->date_dtp) : "";

        if (!empty($debtFines->stringDatePay)){
            $debtFines->stringDatePay = date('Y-m-d', $debtFines->date_pay);
        }
        $debtFines->stringNameCar = $debtFines->carInfo->fullNameMark;
        $debtFines->stringNameDriver = $debtFines->driverInfo->fullName;

        return $this->render('update', [
            'debtFines' => $debtFines,
            'drivers' => $drivers,
            'cars' => $cars,
        ]);
    }

    /**
     * Deletes an existing DebtFines model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id #
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the DebtFines model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id #
     * @return DebtFines the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DebtFines::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionSearchDriverByDateAndCar()
    {
        $date = \Yii::$app->request->post('date');
        $beginDate = \Yii::$app->formatter->asBeginDay($date);
        $endDate = \Yii::$app->formatter->asEndDay($date);
        $result =[
            'date' => \Yii::$app->formatter->asDate($date),
            'car_id' => \Yii::$app->request->post('carId'),
            'driver_day_id' => null,
            'fullname_driver_day' => 'Не назначен',
            'driver_sum_card_day' => 0,
            'driver_phone_day' => null,
            'driver_status_day_shift' => '-',
            'date_close_day_shift' => '-',

            'driver_night_id' => null,
            'fullname_driver_night' => 'Не назначен',
            'driver_sum_card_night' => 0,
            'driver_phone_night' => null,
            'driver_status_night_shift' => "-",
            'date_close_night_shift' => '-',

            'length' => 0
        ];
        $driverData = DriverTabel::find()
            ->where(['car_id' => \Yii::$app->request->post('carId')])
            ->andWhere(['>=', 'work_date', $beginDate])
            ->andWhere(['<=', 'work_date', $endDate])
            ->one();

        if ($driverData){
            $result['car_id'] = (Cars::find()->where(['id' => $driverData->car_id])->one())->fullNameMark;

            if ($driverData->driver_id_day){
                $result['driver_day_id'] = $driverData->driver_id_day;
                $result['fullname_driver_day'] = (Driver::find()->where(['id' => $driverData->driver_id_day])->one())->fullName;
                $result['driver_sum_card_day'] = $driverData->sum_card_day;
                $result['driver_phone_day'] = $driverData->phone_day;
                $result['driver_status_day_shift'] = DriverTabel::labelStatusShift()[$driverData->status_day_shift];
                $result['date_close_day_shift'] = ($driverData->date_close_day_shift == 0) ? "Не закрыта" : \Yii::$app->formatter->asDatetime($driverData->date_close_day_shift);

            }

            if ($driverData->driver_id_night){
                $result['driver_night_id'] = $driverData->driver_id_night;
                $result['fullname_driver_night'] = (Driver::find()->where(['id' => $driverData->driver_id_night])->one())->fullName;
                $result['driver_sum_card_night'] = $driverData->sum_card_night;
                $result['driver_phone_night'] = $driverData->phone_night;
                $result['driver_status_night_shift'] = DriverTabel::labelStatusShift()[$driverData->status_night_shift];
                $result['date_close_night_shift'] = ($driverData->date_close_night_shift == 0) ? "Не закрыта" : \Yii::$app->formatter->asDatetime($driverData->date_close_night_shift);
            }
            $result['length'] = 1;
        }
        return json_encode($result);
    }
}
