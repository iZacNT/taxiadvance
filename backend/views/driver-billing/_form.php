<?php

use common\service\constants\Constants;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\DriverBilling */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="driver-billing-form">

    <?php $form = ActiveForm::begin(); ?>
<div class="col-md-6">

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'balance_yandex')->textInput() ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'bonus_yandex')->textInput() ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'period')->dropDownList(Constants::getPeriod()) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'type_day')->dropDownList(Constants::getDayProperty()) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'fuel')->dropDownList(Constants::getFuel())->label("Тип топлива") ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'input_amount')->textInput() ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'compensations')->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'plan')->textInput() ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'hours')->dropDownList(['12' => "12 Часов", '16' => "16 Часов"]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'summ_driver')->textInput()?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'percent_driver')->textInput()->label("%") ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'summ_park')->textInput()?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'percent_park')->textInput()->label("%") ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'car_phone_summ')->textInput() ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'car_fuel_summ')->textInput() ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'car_wash')->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'depo')->textInput() ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'debt_from_shift')->textInput() ?>
        </div>
    </div>
<div class="row">
    <div class="col-md-8">
        <?= $form->field($model, 'billing')->textInput() ?>
    </div>
    <div class="col-md-4">
        <?= Html::button('Пересчитать', ['class' => 'btn btn-primary', 'style' => 'width:100%; margin-top:31px;', 'id' => 'recalculation']) ?>

    </div>
</div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
</div>
    <?php ActiveForm::end(); ?>

</div>
