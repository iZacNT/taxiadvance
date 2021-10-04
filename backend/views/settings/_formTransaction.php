<?php

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $transactions  */
?>

<div class="transaction-type-form">
    <?php $form = ActiveForm::begin([
            'id' => 'transactionType',
            'action' => '/admin/settings/update-transaction-type',
            ]); ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
    <div class="row">
        <?= $transactions;?>
    </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>