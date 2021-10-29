<?php

namespace backend\controllers;

use app\models\CarRepairs;
use app\models\CarSharing;
use backend\models\Cars;
use backend\models\CarsSearch;
use common\service\car_repare\CarRepareService;
use common\service\car_sharing\CarSharingService;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CarsController implements the CRUD actions for Cars model.
 */
class CarsController extends Controller
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
     * Lists all Cars models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CarsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Cars model.
     * @param int $id #
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $car = $this->findModel($id);
        $carRepairService = new CarRepareService($id);
        $carSharingService = new CarSharingService($id);
        $dataProviderRepairs = $carRepairService->getAllRepairsForDataProvider();
        $dataProviderSharing = $carSharingService->getAllSharingForDataProvider();
        $hasRepair = $carRepairService->hasActiveRepair();

        $idRepair = 0;
        if ($hasRepair){
            $idRepair = (new CarRepareService($id))->activeRepair();
        }
        return $this->render('view', [
            'model' => $car,
            'hasRepair' => $hasRepair,
            'idRepair' => $idRepair,
            'dataProviderRepairs' => $dataProviderRepairs,
            'dataProviderSharing' => $dataProviderSharing
        ]);
    }

    /**
     * Creates a new Cars model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Cars();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Cars model.
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
     * Deletes an existing Cars model.
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
     * Finds the Cars model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id #
     * @return Cars the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Cars::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionGoToRepair():bool
    {
        $car_id = \Yii::$app->request->post('car_id');
        $car = $this->findModel($car_id);
        //$car->status = Cars::STATUS_REPAIR;
        //$car->save();

        $service = new CarRepareService($car_id);
        $service->openRepare();

        return json_encode(true);
    }

    public function actionCloseRepair():bool
    {
        $car_id = \Yii::$app->request->post('car_id');
        $car = $this->findModel($car_id);
        $car->status = Cars::STATUS_WORK;
        $car->save();

        $service = new CarRepareService($car_id);
        $service->closeRepare();

        return json_encode(true);
    }
}
