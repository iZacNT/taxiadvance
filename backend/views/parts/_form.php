<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Parts */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="parts-form">

    <div class="col-md-6">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name_part')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mark')->dropDownList($carMarks, ['prompt' => 'Выберите марку авто']) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    </div>
</div>
