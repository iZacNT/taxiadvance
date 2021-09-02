<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\DriverSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="driver-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'first_name') ?>

    <?= $form->field($model, 'last_name') ?>

    <?= $form->field($model, 'yandex_id') ?>

    <?php // echo $form->field($model, 'driving_license') ?>

    <?php // echo $form->field($model, 'commens') ?>

    <?php // echo $form->field($model, 'passport') ?>

    <?php // echo $form->field($model, 'date_of_issue') ?>

    <?php // echo $form->field($model, 'who_issued_it') ?>

    <?php // echo $form->field($model, 'city') ?>

    <?php // echo $form->field($model, 'street') ?>

    <?php // echo $form->field($model, 'hous') ?>

    <?php // echo $form->field($model, 'corpus') ?>

    <?php // echo $form->field($model, 'appartament') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
