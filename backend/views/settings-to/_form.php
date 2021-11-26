<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\SettingsTo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="settings-to-form">
<div class="col-md-6">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'inspection')->textInput() ?>

    <?= $form->field($model, 'inspection_gas')->textInput() ?>

    <?= $form->field($model, 'inspection_grm')->textInput() ?>

    <?= $form->field($model, 'inspection_gearbox')->textInput() ?>

    <?= $form->field($model, 'inspection_camber')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
</div>
