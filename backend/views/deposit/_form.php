<?php

use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $deposit backend\models\Deposit */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="driver-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="form-group col-md-12">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

<div class="col-md-6">
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($deposit, 'contributed')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($deposit, 'gave')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <label for="dateToChange">Дата</label>
            <?
            echo DatePicker::widget([
                'name'  => 'dateToChange',
                'id' => 'dateToChange',
                'options' => [
                    'placeholder' => Yii::$app->formatter->asDate(time(), "yyyy-MM-dd"),
                    'class'=> 'form-control',
                    'autocomplete'=>'off'
                ],
                'value' => $deposit->created_at,
                'language' => 'ru',
                'dateFormat' => 'yyyy-MM-dd',
                'clientOptions' => [
                    'changeMonth' => true,
                    'changeYear' => true,
                    'yearRange' => '1950:2050',
                    //'showOn' => 'button',
                    //'buttonText' => 'Выбрать дату',
                    //'buttonImageOnly' => true,
                    //'buttonImage' => 'images/calendar.gif'
                ]
            ]);

            ?>
        </div>
</div>
    <?= $form->field($deposit, 'comment')->textarea(['row' => 6]) ?>
</div>
    <div class="form-group">
        <div class="col-md-12">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>
    </div>
    <?= $form->field($deposit, 'created_at')->textInput(['maxlength' => true, 'class' => 'form-control changeDate']) ?>

    <?php ActiveForm::end(); ?>

</div>

<?php
$this->registerJsFile('@web/js/changeDate.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>​