<?php

/* @var $this \yii\web\View */
/* @var $debt \backend\models\Debt */
/* @var $idDriver  */

$this->title = 'Добавить долг';
$this->params['breadcrumbs'][] = ['label' => 'Долги', 'url' => ['driver/view', 'id' => $idDriver]];
$this->params['breadcrumbs'][] = $this->title;

?>
    <div class="deposit-create">
        <?= $this->render('_form', [
            'debt' => $debt,
            'idDriver' => $idDriver,
            'cars' => $cars
        ]) ?>
    </div>