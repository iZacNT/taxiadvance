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
            <div class="col-md-4">
                <?= $form->field($model, 'mileage')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'fuel')->dropDownList(\common\service\constants\Constants::getFuel(), ['prompt' => 'Выберите топливо']) ?>
            </div>
            <div class="col-md-4">
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
        <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Технический осмотр</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <?= $form->field($model, 'inspection')->textInput() ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'inspection_gas')->textInput() ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'inspection_grm')->textInput() ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'inspection_gearbox')->textInput() ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'inspection_camber')->textInput() ?>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        </div>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
