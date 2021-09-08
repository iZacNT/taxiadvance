<?php


namespace backend\controllers;


use backend\models\Filials;
use yii\web\Controller;

class DriverTabel extends Controller
{
    public function actionIndex()
    {
        $filials = (new Filials())->getAllFilials();

        return $this->render('index', [
            'filials' => $filials
        ]);
    }
}