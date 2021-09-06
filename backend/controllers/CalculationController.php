<?php


namespace backend\controllers;

use backend\models\Cars;
use backend\models\Filials;
use common\service\calculation\CalculationService;
use common\service\calculation\PrepareCalculation;
use common\service\constants\Constants;
use yii\web\Controller;

class CalculationController extends Controller
{
    public function actionIndex()
    {
        $filials = (new Filials())->getAllFilials();
        $marks = (new Cars())->getAllMarks();
        $prepare = new PrepareCalculation();
        $prepareMarks = $prepare->prepareCarMarksList($marks);


        return $this->render('index', [
            'prepareMarks' => $prepareMarks,
            'filials' => $filials,
        ]);
    }

    public function actionSearchCalculationForMark()
    {
        $mark = \Yii::$app->request->post("mark");
        $filial = \Yii::$app->request->post("filial");

        return (new CalculationService($filial,$mark, new Constants()))->getCalculationsForMark();
    }

    public function actionCreateTarifsForMark()
    {
        $filial = \Yii::$app->request->post("filial");
        $carMark = \Yii::$app->request->post("carMark");
        $dayLessPlan = \Yii::$app->request->post("dayLessPlan");
        $nightLessPlan = \Yii::$app->request->post("nightLessPlan");
        $dayBiggerPlan = \Yii::$app->request->post("dayBiggerPlan");
        $nightBiggerPlan = \Yii::$app->request->post("nightBiggerPlan");

        $service = new CalculationService($filial, $carMark, new Constants());
        $service->createDayLessPlan($dayLessPlan);
        $service->createNightLessPlan($nightLessPlan);
        $service->createDayBiggerPlan($dayBiggerPlan);
        $service->createNightBiggerPlan($nightBiggerPlan);

        return json_encode([$filial, $carMark, $dayLessPlan, $nightLessPlan, $dayBiggerPlan, $nightBiggerPlan]);
    }

    public function actionUpdateTarifsForMark()
    {
        $filial = \Yii::$app->request->post("filial");
        $carMark = \Yii::$app->request->post("carMark");
        $dayLessPlan = \Yii::$app->request->post("dayLessPlan");
        $nightLessPlan = \Yii::$app->request->post("nightLessPlan");
        $dayBiggerPlan = \Yii::$app->request->post("dayBiggerPlan");
        $nightBiggerPlan = \Yii::$app->request->post("nightBiggerPlan");

        $service = new CalculationService($filial, $carMark, new Constants());
        $service->updateDayLessPlan($dayLessPlan);
        $service->updateNightLessPlan($nightLessPlan);
        $service->updateDayBiggerPlan($dayBiggerPlan);
        $service->updateNightBiggerPlan($nightBiggerPlan);

        return json_encode([$filial, $carMark, $dayLessPlan, $nightLessPlan, $dayBiggerPlan, $nightBiggerPlan]);

    }
}