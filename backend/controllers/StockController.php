<?php

namespace backend\controllers;

use backend\models\CarRepairs;
use backend\models\Parts;
use backend\models\Stock;
use backend\models\StockSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * StockController implements the CRUD actions for Stock model.
 */
class StockController extends Controller
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
     * Lists all Stock models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StockSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Stock model.
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
     * Creates a new Stock model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Stock();
        $carsParts = Parts::find()->allArrayPartsForAutoComplete();
        $openRepairs = CarRepairs::getOpenRepairsForAutocomplete();

        \Yii::debug($openRepairs,__METHOD__);
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        $model->type = 1;

        return $this->render('create', [
            'model' => $model,
            'carsParts' => $carsParts,
            'openRepairs' => $openRepairs
        ]);
    }

    /**
     * Updates an existing Stock model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id #
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $carsParts = Parts::find()->allArrayPartsForAutoComplete();
        $openRepairs = CarRepairs::getOpenRepairsForAutocomplete();

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        foreach($carsParts as $part){
            if ($part['id'] == $model->part_name){
            $model->stringNamePart = $part['label'];
            }
        }

        if($model->type == 2){
            $model->stringNameRepair = $model->repairInfo->id.": ".$model->repairInfo->car->fullNameMark." ".Yii::$app->formatter->asDate($model->repairInfo->date_open_repair);
        }
        return $this->render('update', [
            'model' => $model,
            'carsParts' => $carsParts,
            'openRepairs' => $openRepairs,
        ]);
    }

    public function actionUpdateFromRepair($id,$repair_id)
    {
        $model = $this->findModel($id);
        $carsParts = Parts::find()->allArrayPartsForAutoComplete();
        $openRepairs = CarRepairs::getOpenRepairsForAutocomplete();

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['car-repairs/view', 'id' => $repair_id]);
        }

        foreach($carsParts as $part){
            if ($part['id'] == $model->part_name){
                $model->stringNamePart = $part['label'];
            }
        }

        if($model->type == 2){
            $model->stringNameRepair = $model->repairInfo->id.": ".$model->repairInfo->car->fullNameMark." ".Yii::$app->formatter->asDate($model->repairInfo->date_open_repair);
        }
        return $this->render('update', [
            'model' => $model,
            'carsParts' => $carsParts,
            'openRepairs' => $openRepairs,
        ]);
    }

    /**
     * Deletes an existing Stock model.
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
     * Finds the Stock model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id #
     * @return Stock the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Stock::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
