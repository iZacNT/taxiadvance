<?php

namespace backend\controllers;

use backend\models\DriverBilling;
use backend\models\DriverBillingSearch;
use common\service\driver\BillingDeleteService;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DriverBillingController implements the CRUD actions for DriverBilling model.
 */
class DriverBillingController extends Controller
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
     * Lists all DriverBilling models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DriverBillingSearch();
        $result = $searchModel->search($this->request->queryParams);
        $dataProvider = $result['dataProvider'];
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'calculation' => $result['calculation']
        ]);
    }

    /**
     * Displays a single DriverBilling model.
     * @param int $id №
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
     * Updates an existing DriverBilling model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id №
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
     * Deletes an existing DriverBilling model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id №
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
//        $this->findModel($id)->delete();
        (new BillingDeleteService())->deleteBilling($id);

        return $this->redirect(['index']);
    }

    /**
     * Finds the DriverBilling model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id №
     * @return DriverBilling the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DriverBilling::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionRecalculation()
    {

        return json_encode(\Yii::$app->request->post());
    }
}
