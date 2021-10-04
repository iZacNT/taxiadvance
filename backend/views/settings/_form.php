<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Settings */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="settings-form">
    <?php $form = ActiveForm::begin(['id' => 'settings']); ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Настройки Яндекс API:</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <?= $form->field($model, 'yandex_api')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'yandex_client_id')->textInput(['maxlength' => true]) ?>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col (left) -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Настройки расчета депозитов.</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'depo_min')->textInput()->label("Депозит От"); ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'depo_max')->textInput()->label("Депозит До"); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'les_summ')->textInput()->label("Если меньше:"); ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'more_summ')->textInput()->label("Если больше:"); ?>
                        </div>
                    </div>

                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        </div>

    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

