<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CashRegister */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cash-register-form">
<div class="col-md-6">

    <?php $form = ActiveForm::begin(); ?>
<div class="row">
    <?= $form->field($model, 'type_cash')
        ->radioList($model->getTypeCash(),[
            'item' => function($index, $label, $name, $checked, $value) {

                return '<div class="custom-control custom-radio">'.
            '<input class="custom-control-input" type="radio" id="' . $index . '" name="'.$name.'" value="'.$value.'">'.
            '<label for="' . $index . '" class="custom-control-label">' . ucwords($label) . '</label>'.
            '</div>';
            }
        ]) ?>
</div>

    <?= $form->field($model, 'cash')->textInput() ?>

    <?= $form->field($model, 'comment')->textarea(['rows' => 4]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
</div>
