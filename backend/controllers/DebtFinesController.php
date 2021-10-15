<?php

namespace backend\controllers;

use app\models\DriverTabel;
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
            'model' => $this->findModel($id),
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

                (new DriverDebt(\Yii::$app->request->post('driver_id')))->createDebt($debtFines);

                return $this->redirect(['view', 'id' => \Yii::$app->request->post('id')]);
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
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
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

        $driverData = DriverTabel::find()
            ->where(['car_id' => \Yii::$app->request->post('carId')])
            ->andWhere(['>=', 'work_date', $beginDate])
            ->andWhere(['<=', 'work_date', $endDate])
            ->one();

        $result = [
            'driver_day_id' => $driverData->driver_id_day,
//            'fullname_driver_day' => $driverData->getFullDayDriverName()->last_name,
            'driver_night_id' => $driverData->driver_id_day,
//            'fullname_driver_night' => $driverData->getFullNightDriverName()->last_name,

        ];

        return json_encode($result);
    }
}
