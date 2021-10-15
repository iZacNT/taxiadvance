<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\DebtFinesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="debt-fines-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'driver_id') ?>

    <?= $form->field($model, 'dette') ?>

    <?= $form->field($model, 'back') ?>

    <?= $form->field($model, 'reason') ?>

    <?php // echo $form->field($model, 'comment') ?>

    <?php // echo $form->field($model, 'car_id') ?>

    <?php // echo $form->field($model, 'date_reason') ?>

    <?php // echo $form->field($model, 'regulation') ?>

    <?php // echo $form->field($model, 'geo_dtp') ?>

    <?php // echo $form->field($model, 'date_dtp') ?>

    <?php // echo $form->field($model, 'payable') ?>

    <?php // echo $form->field($model, 'date_pay') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
