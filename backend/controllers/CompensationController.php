<?php

namespace backend\controllers;

use common\service\compensation\CompensationService;
use yii\web\Controller;

class CompensationController extends Controller
{


    public function beforeAction($action)
    {

        if ($this->action->id == 'find-compensation-summ'){
            $this->enableCsrfValidation = false;
        }
        return parent :: beforeAction($action);
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionSaveCompensationSumm()
    {
        $service = new CompensationService();
        return json_encode($service->save(\Yii::$app->request->post()));
    }

    public function actionUpdateCompensationSumm()
    {
        $service = new CompensationService();
        return json_encode($service->update(\Yii::$app->request->post()));
    }

    public function actionFindCompensationSumm()
    {

        $service = new CompensationService(\Yii::$app->request->post());
        return json_encode(array_values($service->findData()));
    }


}