<?php

use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DriverTabelSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="driver-tabel-search col-md-3">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= DatePicker::widget([
        'name'  => 'dateSearchFrom',
        'language' => 'ru',
        'dateFormat' => 'yyyy-MM-dd',
        'options' => [
            'placeholder' => Yii::$app->formatter->asDate(time(), "yyyy-MM-dd"),
            'class' => 'form-control',
            'autocomplete' => 'off'
        ],
        'clientOptions' => [
            'changeMonth' => true,
            'changeYear' => true,
            'yearRange' => '2020:2050',
        ]
    ]) ?>

    <div class="form-group mt-3">
        <?= Html::submitButton('Выбрат дату', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Сбросить', ['class' => 'btn btn-outline-secondary', 'onclick' => 'window.location.replace(window.location.pathname);']); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
