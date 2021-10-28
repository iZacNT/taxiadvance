<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Cars */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cars-form">

    <div class="col-md-6">
    <?php $form = ActiveForm::begin(); ?>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>
        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'mark')->dropDownList($model->getAllMarks(),  ['prompt' => 'Выберите марку']) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'year')->textInput() ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'number')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'fuel')->dropDownList(\common\service\constants\Constants::getFuel(), ['prompt' => 'Выберите топливо']) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'filial')->dropDownList($model->getAllFilials(), ['prompt' => 'Выберите филиал']) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'vin')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'status')->dropDownList($model->getStatusLabel(), ['prompt' => 'Выберите статус']) ?>
            </div>
        </div>

    <?= $form->field($model, 'comment')->textInput(['maxlength' => true]) ?>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'name_insurance')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'name_owner')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'date_osago')->textInput() ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'date_dosago')->textInput() ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'date_kasko')->textInput() ?>
            </div>
        </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
