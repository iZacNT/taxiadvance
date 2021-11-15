<?php

namespace common\service\columns;

use yii\grid\DataColumn;
use yii\helpers\Html;

class VerifyColumn extends DataColumn
{
    public function getDataCellValue($model, $key, $index)
    {
        $html = '<strong>'.$model->shift_id.'</strong><br>';
        if ($model->verify){
            return $html.'<i class="fas fa-user-shield text-green"></i>';
        }else{
            return $html.Html::button('<i class="fas fa-spell-check"></i>',['class' => 'btn btn-warning verifyBtn', 'data-user' => \Yii::$app->user->identity->id, 'data-model' => $model->id]);
        }
    }
}