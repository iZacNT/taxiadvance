<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\DebtFines */

$this->title = 'Добавить штраф ДПС';
$this->params['breadcrumbs'][] = ['label' => 'Штрафы ДПС', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="debt-fines-create">

    <?= $this->render('_form', [
        'debtFines' => $debtFines,
        'drivers' => $drivers,
        'cars' => $cars,
    ]) ?>

</div>
