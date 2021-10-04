<?php

/* @var $this \yii\web\View */
/* @var $debt \backend\models\Debt */
/* @var $idDriver  */

use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;


?>

<div class="driver-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="form-group col-md-12">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

<div class="row">
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-4">
                <?= $form->field($debt, 'dette')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($debt, 'back')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-4">

                <?= $form->field($debt, 'stringDateReason')->widget(\yii\jui\DatePicker::classname(), [
                    'language' => 'ru',
                    'dateFormat' => 'yyyy-MM-dd',
                    'options' => [
                        'placeholder' => Yii::$app->formatter->asDate(
                            (!empty($debt->date_reason))? $debt->date_reason : time(), "yyyy-MM-dd"
                        ),
                        'class'=> 'form-control',
                    ],
                    'clientOptions' => [
                        'changeMonth' => true,
                        'changeYear' => true,
                        'yearRange' => '2020:2050',
                    ],
                ]) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($debt, 'reason')->dropDownList($debt->debtReasons,['prompt' => 'Выберите основание']) ?>
            </div>
            <div class="col-md-6">

                <?
                echo $form->field($debt, 'stringNameCar')
                    ->widget(\yii\jui\AutoComplete::classname(), [
                        //'value' => (!empty($model->floor) ? $model->floor : ''),
                        'clientOptions' => [
                            'source' => $cars,
                            'minLength'=>'0',
                            'autoFill'=>true,
                            'select' => new JsExpression("function( event, ui ) {
                        $('#debt-car_id').val(ui.item.id);
                }")],
                        'options'=>[
                            'class'=>'form-control',
                            'id' => 'autocompleteCar',
                            'placeholder' => 'Автомобиль',
                        ],

                    ]);
                ?>

            </div>
        </div>

        <?= $form->field($debt, 'comment')->textarea(['row' => 6]) ?>

    </div>
    <div class="col-md-6" id="regulationData">
        <?= $form->field($debt, 'regulation')->widget(\yii\widgets\MaskedInput::class, [
            'mask' => '99999999999999999999',
            'options' => [
                    'placeholder' => 'Введите № постановления 20 символов'
            ],
        ]) ?>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($debt, 'geo_dtp')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($debt, 'stringDateDtp')->widget(\yii\jui\DatePicker::classname(), [
                    'language' => 'ru',
                    'dateFormat' => 'yyyy-MM-dd',
                    'options' => [
                        'placeholder' => Yii::$app->formatter->asDate(
                            (!empty($debt->date_dtp))? $debt->date_dtp : time(), "yyyy-MM-dd"),
                        'class'=> 'form-control',
                        'autocomplete'=>'off'
                    ],
                    'clientOptions' => [
                        'changeMonth' => true,
                        'changeYear' => true,
                        'yearRange' => '2020:2050',
                    ]
                ]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($debt, 'payable')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($debt, 'stringDatePay')->widget(\yii\jui\DatePicker::classname(), [
                    'language' => 'ru',
                    'dateFormat' => 'yyyy-MM-dd',
                    'options' => [
                        'placeholder' => Yii::$app->formatter->asDate(
                            (!empty($debt->date_pay))? $debt->date_pay : time(), "yyyy-MM-dd"),
                        'class'=> 'form-control',
                        'autocomplete'=>'off'
                    ],
                    'clientOptions' => [
                        'changeMonth' => true,
                        'changeYear' => true,
                        'yearRange' => '2020:2050',
                    ]
                ]) ?>
            </div>
        </div>
    </div>
</div>
    <div class="form-group">
        <div class="col-md-12">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>
    </div>
</div>

<?= $form->field($debt, 'car_id')->hiddenInput()->label(""); ?>

    <?php ActiveForm::end(); ?>

<?php
$js = <<< JS

    function hideReasonData()
    {
        if ($("#debt-reason").val() == 1) {
            $("#regulationData").show();
        }else{
            $("#regulationData").hide();
        }
    }
    hideReasonData();
    $(".driver-form").on("change", '#debt-reason', hideReasonData);
JS;

$this->registerJs( $js, $position = yii\web\View::POS_READY, $key = null );
?>​