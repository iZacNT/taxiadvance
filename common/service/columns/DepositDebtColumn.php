<?php

namespace common\service\columns;

use common\service\driver\DriverDebt;
use common\service\driver\DriverDeposit;
use yii\grid\DataColumn;

class DepositDebtColumn extends DataColumn
{
    public function getDataCellValue($model, $key, $index)
    {
        $deposit =  (new DriverDeposit($model->id))->getSummDeposit();
        $debt = (new DriverDebt($model->id))->getSummDebt();
        $calculate = $deposit-$debt;
        $color = ($calculate<0) ? 'text-red' : 'text-green';
        return "<strong>Депозит: </strong>".\Yii::$app->formatter->asCurrency($deposit)
            ."&nbsp;&nbsp;&nbsp;&nbsp;<strong>Долг: </strong>".\Yii::$app->formatter->asCurrency($debt).
            "<br><strong>Разница: </strong><span class='".$color."' style='font-weight: bold'>".\Yii::$app->formatter->asCurrency($calculate)."</span>";
    }
}