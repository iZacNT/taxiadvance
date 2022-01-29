<?php

namespace backend\controllers;

use backend\models\CashRegister;
use backend\models\CashRegisterSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CashRegisterController implements the CRUD actions for CashRegister model.
 */
class CashRegisterController extends Controller
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
     * Lists all CashRegister models.
     * @return mixed
     */
    public function actionIndex()
    {
        $cr = new CashRegister();
        $cashRegistryWithDolg = $cr->getSummInCashRegisterWithDolg();
        $searchModel = new CashRegisterSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'cashRegistryWithDolg' => $cashRegistryWithDolg
        ]);
    }

    /**
     * Displays a single CashRegister model.
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
     * Creates a new CashRegister model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CashRegister();

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
     * Updates an existing CashRegister model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
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
     * Deletes an existing CashRegister model.
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
     * Finds the CashRegister model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return CashRegister the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CashRegister::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionSaveCashRegister(){

        $cashNal = \Yii::$app->request->post("cashNal");
        $cashInRegister = \Yii::$app->request->post("cashInRegister");
        $amount = $cashNal-$cashInRegister;

        $cashRegister = new CashRegister();
        $cashRegister->type_cash = \Yii::$app->request->post("type_cash");
        $cashRegister->cash = $cashInRegister;
        $cashRegister->comment = $this->getComment($cashNal, $amount);
        $cashRegister->date_time = time();

        \Yii::debug($cashNal,__METHOD__);
        \Yii::debug($cashInRegister,__METHOD__);
        \Yii::debug($amount,__METHOD__);
        \Yii::debug($this->getComment($cashNal, $amount),__METHOD__);

        $cashRegister->save();

        //$this->saveDifference(\Yii::$app->request->post("amount")); //Добавление разницы в таблицу Касса
        return json_encode(\Yii::$app->request->post());
    }

    public function getComment($cashNal, $amount):string
    {
        $comment = "Суммы в кассе и наличные сходятся!";
        if ($amount > 0){
            $comment = "Наличных: ".$cashNal."руб. Больше чем отражено в кассе, на: ".$amount;
        }
        if ($amount < 0){
            $comment = "Наличных: ".$cashNal."руб. Меньше чем отражено в кассе, на: ".$amount*(-1);
        }
        return $comment;
    }

    public function saveDifference($amount):void
    {
        $typeCash=0;
        $cashRegistry = new CashRegister();

        if ($amount != 0){
            if ($amount > 0){
                $typeCash = $cashRegistry::TYPE_PRIHOD;
            }
            if ($amount < 0){
                $typeCash = $cashRegistry::TYPE_RASHOD;
            }

            $cashRegistry->type_cash = $typeCash;
            $cashRegistry->cash = $amount;
            $cashRegistry->comment = "Разница между кассой и занесенными данными.";
            $cashRegistry->date_time = time()+1;
            $cashRegistry->save();
        }
    }
}
