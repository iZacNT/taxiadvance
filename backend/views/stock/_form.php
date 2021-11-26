<?php

use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Stock */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="stock-form">

    <div class="col-md-6">

    <?php $form = ActiveForm::begin(); ?>

        <?
        echo $form->field($model, 'stringNamePart')
            ->widget(\yii\jui\AutoComplete::classname(), [
                'clientOptions' => [
                    'source' => $carsParts,
                    'minLength'=>'0',
                    'autoFill'=>true,
                    'select' => new JsExpression("function( event, ui ) {
                        $('#stock-part_name').val(ui.item.id);
                }")],
                'options'=>[
                    'class'=>'form-control',
                    'id' => 'autocompleteCar',
                    'placeholder' => 'Наименование детали',
                ],

            ]);
        ?>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'type')->radioList($model->typeStock) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'count')->textInput(['maxlength' => true]) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'invoice')->textInput(['maxlength' => true, 'disabled' => !(($model->type == 1))]) ?>
            </div>
            <div class="col-md-6">
                <?
                echo $form->field($model, 'stringNameRepair')
                    ->widget(\yii\jui\AutoComplete::classname(), [
                        'clientOptions' => [
                            'source' => $openRepairs,
                            'minLength'=>'0',
                            'autoFill'=>true,
                            'select' => new JsExpression("function( event, ui ) {
                        $('#stock-repair_id').val(ui.item.id);
                }")],
                        'options'=>[
                            'class'=>'form-control',
                            'id' => 'autocompleteCarRepair',
                            'disabled' => !(($model->type == 2)),
                            'placeholder' => 'Ремонт',
                        ],

                    ]);
                ?>
            </div>
        </div>

    <?= $form->field($model, 'comment')->textarea(['rows' => 3]) ?>


    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

        <?= $form->field($model, 'part_name')->hiddenInput(['maxlength' => true])->label("") ?>
        <?= $form->field($model, 'repair_id')->hiddenInput(['maxlength' => true])->label("")  ?>


        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php
$this->registerJsFile('@web/js/stock/editTypeStock.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>​
