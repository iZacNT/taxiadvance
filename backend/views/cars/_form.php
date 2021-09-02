<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Cars */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cars-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'mark')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'year')->textInput() ?>

    <?= $form->field($model, 'number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vin')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'comment')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name_insurance')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date_osago')->textInput() ?>

    <?= $form->field($model, 'date_dosago')->textInput() ?>

    <?= $form->field($model, 'date_kasko')->textInput() ?>

    <?= $form->field($model, 'name_owner')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fuel')->textInput() ?>

    <?= $form->field($model, 'filial')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
