<?php

namespace backend\controllers;

use app\models\CarSharing;
use backend\models\Cars;
use backend\models\CarSharingSearch;
use backend\models\Driver;
use common\service\car_repare\CarRepareService;
use common\service\car_sharing\CarSharingService;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CarSharingController implements the CRUD actions for CarSharing model.
 */
class CarSharingController extends Controller
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
     * Lists all CarSharing models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CarSharingSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CarSharing model.
     * @param int $id ID
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
     * Creates a new CarSharing model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        $model = new CarSharing();

        $drivers = Driver::prepareDriversForAutocomplete();

        $car_id = \Yii::$app->request->get("id");
        $carSharingService = new CarSharingService($car_id);
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $carSharingService->createSharing($model);
                return $this->redirect(['cars/view', 'id' => $model->car_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        $model->car_id = $car_id;

        return $this->render('create', [
            'model' => $model,
            'drivers' => $drivers
        ]);
    }

    /**
     * Updates an existing CarSharing model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $carSharingService = new CarSharingService($model->car_id);
        $drivers = Driver::prepareDriversForAutocomplete();
        if ($this->request->isPost && $model->load($this->request->post())) {

            $model->date_start = strtotime($model->stringDateStart);
            $model->date_stop = strtotime($model->stringDateStop);

            $model->save();
            return $this->redirect(['cars/view', 'id' => $model->car_id]);
        }

        foreach($drivers as $driver){
            if ($driver['id'] == $model->driver_id){
                $model->stringNameDriver = $driver['label'];
            }
        }

        $model->stringDateStart = (!empty($model->date_start)) ? \Yii::$app->formatter->asDate($model->date_start, "yyyy-MM-dd"): '';
        $model->stringDateStop = (!empty($model->date_stop)) ? \Yii::$app->formatter->asDate($model->date_stop, "yyyy-MM-dd"): '';

        return $this->render('update', [
            'model' => $model,
            'drivers' => $drivers
        ]);
    }

    /**
     * Deletes an existing CarSharing model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $car_id = $model->car_id;
        $model->delete();

        return $this->redirect(['cars/view', 'id' => $model->car_id]);
    }

    /**
     * Finds the CarSharing model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return CarSharing the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CarSharing::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
