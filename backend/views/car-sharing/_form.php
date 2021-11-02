<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CarSharing */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="car-sharing-form">

    <div class="col-md-6">

    <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'stringDateStart')->widget(\yii\jui\DatePicker::classname(), [
                    'options' => [
                        'placeholder' => Yii::$app->formatter->asDate((!empty($model->date_start)) ? $model->date_start : time(), "yyyy-MM-dd"),
                        'class'=> 'form-control',
                        'autocomplete'=>'off'
                    ],
                    'language' => 'ru',
                    'dateFormat' => 'yyyy-MM-dd',
                    'clientOptions' => [
                        'changeMonth' => true,
                        'changeYear' => true,
                        'yearRange' => '2020:2050',
                    ]
                ]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'stringDateStop')->widget(\yii\jui\DatePicker::classname(), [
                    'options' => [
                        'placeholder' => Yii::$app->formatter->asDate((!empty($model->date_stop)) ? $model->date_stop : time(), "yyyy-MM-dd"),
                        'class'=> 'form-control',
                        'autocomplete'=>'off'
                    ],
                    'language' => 'ru',
                    'dateFormat' => 'yyyy-MM-dd',
                    'clientOptions' => [
                        'changeMonth' => true,
                        'changeYear' => true,
                        'yearRange' => '2020:2050',
                    ]
                ]) ?>
            </div>
        </div>

    <?= $form->field($model, 'comments')->textarea(['rows' => 4]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

        <?= $form->field($model, 'car_id')->hiddenInput()->label("") ?>

        <?php ActiveForm::end(); ?>

    </div>

</div>