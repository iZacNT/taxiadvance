<?php

use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Driver */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="driver-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="form-group col-md-12">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

<div class="row">
    <div class="col-md-6">

        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'status')->dropDownList($model->statusDriver, ['prompt' => 'Выберите статус']) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'filial')->dropDownList($model->getAllFilials(), ['prompt' => 'Выберите филиал']) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'stringShiftClosing')->widget(\yii\widgets\MaskedInput::class, [
                    'mask' => '9999-99-99 99:99',
                    'type' => 'text',
                    'options' => ['placeholder' => date("yy-m-d H:i")],
                    'value' => $model->stringShiftClosing,
                ]) ?>
            </div>

        </div>
        <div class="row">
            <div class="col-md-3">
                <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'driving_license')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'yandex_id')->textInput(['maxlength' => true]) ?>
            </div>

        </div>
        <?= $form->field($model, 'commens')->textarea(['rows' => 6]) ?>
    </div>

    <div class="col-md-6">

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'passport')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-6">

                <?= $form->field($model, 'stringDateIssused')->widget(\yii\jui\DatePicker::classname(), [
                    'options' => [
                        'placeholder' => Yii::$app->formatter->asDate(($model->date_of_issue)? $model->date_of_issue : time(), "yyyy-MM-dd"),
                        'class'=> 'form-control',
                        'autocomplete'=>'off'
                    ],
                    'language' => 'ru',
                    'dateFormat' => 'yyyy-MM-dd',
                    'clientOptions' => [
                        'changeMonth' => true,
                        'changeYear' => true,
                        'yearRange' => '2015:2050',
                    ]
                ]) ?>

            </div>
            <div class="col-md-12">
                <?= $form->field($model, 'who_issued_it')->textInput(['maxlength' => true]) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'street')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'hous')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'corpus')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'appartament')->textInput(['maxlength' => true]) ?>
            </div>
        </div>



    </div>
</div>
    <div class="form-group row">
        <div class="col-md-12">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?= $form->field($model, 'date_of_issue')->hiddenInput(['maxlength' => true, 'class' => 'form-control changeDate'])->label("") ?>

    <?php ActiveForm::end(); ?>

</div>
<?php

$this->registerJsFile('@web/js/changeDate.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>​