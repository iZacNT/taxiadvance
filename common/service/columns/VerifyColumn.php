<?php

namespace common\service\columns;

use yii\grid\DataColumn;
use yii\helpers\Html;

class VerifyColumn extends DataColumn
{
    public function getDataCellValue($model, $key, $index)
    {
        if ($model->verify){
            return '<i class="fas fa-user-shield text-green"></i>';
        }else{
            return Html::button('<i class="fas fa-spell-check"></i>',['class' => 'btn btn-warning verifyBtn', 'data-user' => \Yii::$app->user->identity->id, 'data-model' => $model->id]);
        }
    }
}