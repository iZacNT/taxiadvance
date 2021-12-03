<?php

namespace backend\controllers;

use backend\models\CarRepairs;
use backend\models\CarRepairsSearch;
use backend\models\Cars;
use backend\models\Parts;
use backend\models\Stock;
use common\service\constants\Constants;
use common\service\stock\StockService;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CarRepairsController implements the CRUD actions for CarRepairs model.
 */
class CarRepairsController extends Controller
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
     * Lists all CarRepairs models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CarRepairsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CarRepairs model.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $parts = (new Parts())->getPartsByMark($model->car->mark);
        \Yii::debug($parts, __METHOD__);
        $dataProviderStock = new ActiveDataProvider([
            'query' => Stock::find()->where(['repair_id' => $id])
        ]);

        return $this->render('view', [
            'model' => $model,
            'dataProviderStock' => $dataProviderStock,
            'parts' => $parts
        ]);
    }

    /**
     * Creates a new CarRepairs model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CarRepairs();
        $cars = Cars::prepareCarsForAutocomplete(\Yii::$app->user->identity->getFilialUser());
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->date_open_repair = strtotime($model->stringDateOpenRepair);
                $model->date_close_repare = ($model->stringDateCloseRepair)? strtotime($model->stringDateCloseRepair): null;

                $model->save();
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'cars' => $cars
        ]);
    }

    /**
     * Updates an existing CarRepairs model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $cars = Cars::prepareCarsForAutocomplete(\Yii::$app->user->identity->getFilialUser());

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            $model->date_open_repair = strtotime($model->stringDateOpenRepair);
            $model->date_close_repare = ($model->stringDateCloseRepair)? strtotime($model->stringDateCloseRepair): null;

            if (empty($model->date_close_repare) && $model->status == CarRepairs::STATUS_CLOSE_REPAIR){
                $model->date_close_repare = time();
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        $model->stringDateOpenRepair = ($model->date_open_repair)? \Yii::$app->formatter->asDatetime($model->date_open_repair) : null;
        $model->stringDateCloseRepair = ($model->date_close_repare)? \Yii::$app->formatter->asDatetime($model->date_close_repare) : null;

        foreach($cars as $car){
            if ($car['id'] == $model->car_id){
                $model->stringNameCar = $car['label'];
            }
        }

        return $this->render('update', [
            'model' => $model,
            'cars' => $cars
        ]);
    }

    /**
     * Deletes an existing CarRepairs model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the CarRepairs model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return CarRepairs the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CarRepairs::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionSavePartsForRepair()
    {
        $parts = \Yii::$app->request->post("data");
        $resultArr = [];
        foreach($parts as $part){
            $stock = (new StockService())->create(
                $part['name_parts'],
                $part['count'],
                2,
                $part['id_repair']
            );
            if ($stock->errors){
                $resultArr[] = ['type' => 'false' ,'message' => "Деталь не добавлена"];
            }else{
                $resultArr[] = ['type' => 'true' ,'message' => $stock->partInfo->name_part . ": " . $stock->count . "шт. добавлено к Ремонту"];
            }
        }
        return json_encode($resultArr);
    }
}
