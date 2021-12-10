<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\DriverBillingSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="driver-billing-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'type_day') ?>

    <?= $form->field($model, 'plan') ?>

    <?= $form->field($model, 'summ_driver') ?>

    <?= $form->field($model, 'summ_park') ?>

    <?= $form->field($model, 'percent_driver') ?>

    <?php // echo $form->field($model, 'percent_park') ?>

    <?php // echo $form->field($model, 'billing') ?>

    <?php // echo $form->field($model, 'hours') ?>

    <?php // echo $form->field($model, 'car_phone_summ') ?>

    <?php // echo $form->field($model, 'car_fuel_summ') ?>

    <?php // echo $form->field($model, 'car_wash') ?>

    <?php // echo $form->field($model, 'debt_from_shift') ?>

    <?php // echo $form->field($model, 'depo') ?>

    <?php // echo $form->field($model, 'input_amount') ?>

    <?php // echo $form->field($model, 'period') ?>

    <?php // echo $form->field($model, 'fuel') ?>

    <?php // echo $form->field($model, 'car_mark') ?>

    <?php // echo $form->field($model, 'bonus_yandex') ?>

    <?php // echo $form->field($model, 'balance_yandex') ?>

    <?php // echo $form->field($model, 'date_billing') ?>

    <?php // echo $form->field($model, 'driver_id') ?>

    <?php // echo $form->field($model, 'id') ?>

    <?php // echo $form->field($model, 'compensations') ?>

    <?php // echo $form->field($model, 'car_id') ?>

    <?php // echo $form->field($model, 'shift_id') ?>

    <?php // echo $form->field($model, 'verify') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
