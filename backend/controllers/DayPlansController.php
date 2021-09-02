<?php


namespace backend\controllers;


use app\models\DayPlans;
use backend\models\Filials;
use yii\data\ActiveDataProvider;
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
}