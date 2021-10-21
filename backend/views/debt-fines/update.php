<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $debtFines backend\models\DebtFines */

$this->title = 'Изменить данные штрафа: ' . $debtFines->driverInfo->fullName ." от ".$debtFines->stringDateReason;
$this->params['breadcrumbs'][] = ['label' => 'Штрафы ДПС', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $debtFines->driverInfo->fullName ." от ".$debtFines->stringDateReason, 'url' => ['view', 'id' => $debtFines->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="debt-fines-update">

    <?= $this->render('_form', [
        'debtFines' => $debtFines,
        'drivers' => $drivers,
        'cars' => $cars,
    ]) ?>

</div>
