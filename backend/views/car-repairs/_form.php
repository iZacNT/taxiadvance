<?php

use kartik\datetime\DateTimePicker;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\CarRepairs */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="car-repairs-form">
<div class="col-md-6">
    <?php $form = ActiveForm::begin(); ?>

    <?
    echo $form->field($model, 'stringNameCar')
        ->widget(\yii\jui\AutoComplete::classname(), [
            'clientOptions' => [
                'source' => $cars,
                'minLength'=>'0',
                'autoFill'=>true,
                'select' => new JsExpression("function( event, ui ) {
                        $('#carrepairs-car_id').val(ui.item.id);
                }")],
            'options'=>[
                'class'=>'form-control',
                'id' => 'autocompleteCar',
                'placeholder' => 'Автомобиль',
            ],

        ]);
    ?>

    <div class="row">
        <div class="col-md-6">
            <?=
             $form->field($model, 'stringDateOpenRepair')->widget(DateTimePicker::classname(), [
                'options' => ['placeholder' => 'Введие дату/время'],
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'dd-mm-yyyy hh:ii'
                ]
            ]);
            ?>
        </div>
        <div class="col-md-6">
            <?=
            $form->field($model, 'stringDateCloseRepair')->widget(DateTimePicker::classname(), [
                'options' => ['placeholder' => 'Введие дату/время'],
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'dd-mm-yyyy hh:ii'
                ]
            ]);
            ?>
        </div>
    </div>

    <?= $form->field($model, 'status')->dropDownList($model->getStatusLabel(),['prompt' => 'Выберите статус']) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?= $form->field($model, 'car_id')->hiddenInput()->label(""); ?>


    <?php ActiveForm::end(); ?>
</div>
</div>
