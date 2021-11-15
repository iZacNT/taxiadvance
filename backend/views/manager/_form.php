<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $managerForm backend\models\ManagerForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="manager-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($managerForm, 'first_name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($managerForm, 'last_name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($managerForm, 'phone')->textInput(['maxlength' => true]) ?>

            <?= $form->field($managerForm, 'filial')->textInput() ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($managerForm, 'username')->textInput(['maxlength' => true]) ?>

            <?= $form->field($managerForm, 'password')->textInput(['maxlength' => true]) ?>

            <?= $form->field($managerForm, 'email')->textInput(['maxlength' => true]) ?>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($managerForm, 'status')->dropDownList($managerForm->statusArray) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($managerForm, 'role')->dropDownList($managerForm->roleArray,['prompt' => 'Выберете роль пользователя']) ?>
                </div>
            </div>
        </div>
    </div>



    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
