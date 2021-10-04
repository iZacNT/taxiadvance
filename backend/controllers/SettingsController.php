<?php

namespace backend\controllers;

use backend\models\Settings;
use backend\models\TransactionTypes;
use common\service\settings\PrepareTransactionTypeService;
use common\service\yandex\params\ParamsTransactionCategoryList;
use common\service\yandex\YandexApi;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SettingsController implements the CRUD actions for Settings model.
 */
class SettingsController extends Controller
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
     * Updates an existing Settings model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate()
    {
        $settings = $this->findModel(1);

        if ($this->request->isPost && $settings->load($this->request->post()) && $settings->save()) {
            return $this->redirect(['update', 'id' => $settings->id]);
        }
        $transactionList = new ParamsTransactionCategoryList($settings->yandex_client_id);
        $req = new YandexApi(
            'https://fleet-api.taxi.yandex.net/v2/parks/transactions/categories/list',
            $settings->yandex_client_id,
            $settings->yandex_api,
            $transactionList
        );
        $listTType = '';
        if ($req)
        {
            $transactionService = new PrepareTransactionTypeService($req->request()['categories']);
            $transactionService->verifyWithDBase();
            $listTType = $transactionService->preparePreviewTransactionType();
        }

        return $this->render('update', [
            'model' => $settings,
            'transactions' => $listTType
        ]);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionUpdateTransactionType()
    {

        $settings = $this->findModel(1);


        $types = \Yii::$app->request->post("transactionType");
        if ($types){
            TransactionTypes::updateAll(['summarize' => TransactionTypes::UN_SUMMARIZE]);
            foreach($types as $key => $value){
                $transactionTypes = TransactionTypes::find()->where(['type' => $value])->one();
                $transactionTypes->summarize = TransactionTypes::SUMMARIZE;
                $transactionTypes->save();
                \Yii::debug(serialize($transactionTypes->errors), __METHOD__);
            }
        }

        $transactionList = new ParamsTransactionCategoryList($settings->yandex_client_id);
        $req = new YandexApi(
            'https://fleet-api.taxi.yandex.net/v2/parks/transactions/categories/list',
            $settings->yandex_client_id,
            $settings->yandex_api,
            $transactionList
        );
        $listTType = '';
        if ($req)
        {
            $transactionService = new PrepareTransactionTypeService($req->request()['categories']);
            $transactionService->verifyWithDBase();
            $listTType = $transactionService->preparePreviewTransactionType();
        }

        return $this->render('update', [
            'model' => $settings,
            'transactions' => $listTType
        ]);
    }

    /**
     * Finds the Settings model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Settings the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Settings::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
