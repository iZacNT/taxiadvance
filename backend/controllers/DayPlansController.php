<?php


namespace backend\controllers;


use backend\models\DayPlans;
use backend\models\Filials;
use common\service\dayPlans\PrepareDayPlans;
use yii\web\Controller;

class DayPlansController extends Controller
{
    public function actionIndex()
    {
       $filials = (new Filials())->getAllFilials();

        return $this->render('index', [
            'filials' => $filials
        ]);
    }

    public function actionSearchDayPlans()
    {
        $filial = \Yii::$app->request->post('filial');
        $plans = DayPlans::find()->where(['filial' => $filial])->all();
        $prepareService = new PrepareDayPlans();
        $arrDayPlans = $prepareService->prepareDayPlansArr($plans);

        return json_encode($arrDayPlans);
    }

    public function actionCreateDayPlans()
    {
        $filial = \Yii::$app->request->post('filial');
        $weekendDay = \Yii::$app->request->post('weekendDay');
        $weekenNight = \Yii::$app->request->post('weekenNight');
        $workingDay = \Yii::$app->request->post('workingDay');
        $workingNight = \Yii::$app->request->post('workingNight');

        $prepareService = new PrepareDayPlans();
        $arrDayPlans = $prepareService->create(
            $filial,
            $weekendDay,
            $weekenNight,
            $workingDay,
            $workingNight
        );

        return json_encode($arrDayPlans);
    }

    public function actionUpdateDayPlans()
    {
        $filial = \Yii::$app->request->post('filial');
        $weekendDay = \Yii::$app->request->post('weekendDay');
        $weekenNight = \Yii::$app->request->post('weekenNight');
        $workingDay = \Yii::$app->request->post('workingDay');
        $workingNight = \Yii::$app->request->post('workingNight');

        $prepareService = new PrepareDayPlans();
        $arrDayPlans = $prepareService->update(
            $filial,
            $weekendDay,
            $weekenNight,
            $workingDay,
            $workingNight
        );

        return json_encode($arrDayPlans);
    }

}