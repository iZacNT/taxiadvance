<?php

use yii\helpers\Html;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DriverTabel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="driver-tabel-form">

    <div class="col-md-6">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <?php if( Yii::$app->session->hasFlash('error') ): ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?php echo Yii::$app->session->getFlash('error'); ?>
            </div>
        <?php endif;?>

        <div class="col-md-6">

        <?
            echo $form->field($driverTabel, 'stringNameCar')
            ->widget(\yii\jui\AutoComplete::classname(), [
            //'value' => (!empty($model->floor) ? $model->floor : ''),
                'clientOptions' => [
                    'source' => $cars,
                    'minLength'=>'0',
                    'autoFill'=>true,
                    'select' => new JsExpression("function( event, ui ) {
                        $('#drivertabel-car_id').val(ui.item.id);
                }")],
                'options'=>[
                    'class'=>'form-control',
                    'id' => 'autocompleteCar',
                    'placeholder' => 'Автомобиль',
                ],

            ]);
            ?>
        </div>
        <div class="col-md-6">

            <?= $form->field($driverTabel, 'work_date', ['enableClientValidation' => false])->widget(\yii\jui\DatePicker::classname(), [
                'options' => [
                    'placeholder' => Yii::$app->formatter->asDate(($driverTabel->work_date)? $driverTabel->work_date : time(), "yyyy-MM-dd"),
                    'class'=> 'form-control',
                    'autocomplete'=>'off'
                ],
                'language' => 'ru',
                'dateFormat' => 'yyyy-MM-dd',
                'clientOptions' => [
                    'changeMonth' => true,
                    'changeYear' => true,
                    'yearRange' => '2020:2050',
                ]
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">

            <?
            echo $form->field($driverTabel, 'stringDriverDay')
                ->widget(\yii\jui\AutoComplete::classname(), [
                    'clientOptions' => [
                        'source' => $drivers,
                        'minLength'=>'0',
                        'autoFill'=>true,
                        'select' => new JsExpression("function( event, ui ) {
                        $('#drivertabel-driver_id_day').val(ui.item.id);
                }")],
                    'options'=>[
                        'class'=>'form-control',
                        'id' => 'autocompleteDriverDay',
                        'placeholder' => 'Водитель дневной смены',
                    ],

                ]);
            ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($driverTabel, 'card_day')->textInput() ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($driverTabel, 'phone_day')->textInput() ?>
        </div>
    </div>
        <div class="row">
            <div class="col-md-6">

                <?
                echo $form->field($driverTabel, 'stringDriverNight')
                    ->widget(\yii\jui\AutoComplete::classname(), [
                        'clientOptions' => [
                            'source' => $drivers,
                            'minLength'=>'0',
                            'autoFill'=>true,
                            'select' => new JsExpression("function( event, ui ) {
                        $('#drivertabel-driver_id_night').val(ui.item.id);
                }")],
                        'options'=>[
                            'class'=>'form-control',
                            'id' => 'autocompleteDriverNight',
                            'placeholder' => 'Водитель ночной смены',
                        ],

                    ]);
                ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($driverTabel, 'card_night')->textInput() ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($driverTabel, 'phone_night')->textInput() ?>
            </div>
        </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

        <?= $form->field($driverTabel, 'car_id')->hiddenInput()->label("") ?>
        <?= $form->field($driverTabel, 'driver_id_day')->hiddenInput()->label("")  ?>
        <?= $form->field($driverTabel, 'driver_id_night')->hiddenInput()->label("") ?>


        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php
$js = <<< JS
$("#autocompleteDriverDay").on('change',function (){
    if ($(this).val() === "") {
        $("#drivertabel-driver_id_day").val("");
    }
});

$("#autocompleteDriverNight").on('change',function (){
    if ($(this).val() === "") {
        $("#drivertabel-driver_id_night").val("");
    }
});
JS;

$this->registerJs( $js, $position = yii\web\View::POS_READY, $key = null );
?>​
