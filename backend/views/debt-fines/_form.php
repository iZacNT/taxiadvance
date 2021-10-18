<?php

use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $debtFines backend\models\DebtFines */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="debt-fines-form">


    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <div class="row">

    <div class="col-md-6">
        <div class="row">
            <div class="col-md-4">
                <?= $form->field($debtFines, 'stringDateReason')->widget(\yii\jui\DatePicker::classname(), [
                    'language' => 'ru',
                    'dateFormat' => 'yyyy-MM-dd',
                    'options' => [
                        'placeholder' => Yii::$app->formatter->asDate(
                            (!empty($debtFines->date_reason))? $debtFines->date_reason : time(), "yyyy-MM-dd"
                        ),
                        'class'=> 'form-control',
                    ],
                    'clientOptions' => [
                        'changeMonth' => true,
                        'changeYear' => true,
                        'yearRange' => '2020:2050',
                    ],
                ]) ?>
            </div>
            <div class="col-md-4">
                <?
                echo $form->field($debtFines, 'stringNameCar')
                    ->widget(\yii\jui\AutoComplete::classname(), [
                        //'value' => (!empty($model->floor) ? $model->floor : ''),
                        'clientOptions' => [
                            'source' => $cars,
                            'minLength'=>'0',
                            'autoFill'=>true,
                            'select' => new JsExpression("function( event, ui ) {
                        $('#debtfines-car_id').val(ui.item.id);
                }")],
                        'options'=>[
                            'class'=>'form-control',
                            'id' => 'autocompleteCar',
                            'placeholder' => 'Автомобиль',
                        ],

                    ]);
                ?>
            </div>
            <div class="col-md-4">
                <?
                    echo Html::button('Найти водителя', ['class' => 'btn btn-primary', 'style' => ' margin-top: 15%', 'id' => 'findDriver'])
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <?
                echo $form->field($debtFines, 'stringNameDriver')
                    ->widget(\yii\jui\AutoComplete::classname(), [
                        //'value' => (!empty($model->floor) ? $model->floor : ''),
                        'clientOptions' => [
                            'source' => $drivers,
                            'minLength'=>'0',
                            'autoFill'=>true,
                            'select' => new JsExpression("function( event, ui ) {
                        $('#debtfines-driver_id').val(ui.item.id);
                }")],
                        'options'=>[
                            'class'=>'form-control',
                            'id' => 'autocompleteDriver',
                            'placeholder' => 'Водитель',
                        ],

                    ]);
                ?>
            </div>
            <div class="col-md-4">

            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($debtFines, 'dette')->textInput() ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($debtFines, 'back')->textInput() ?>
            </div>
        </div>

    <?= $form->field($debtFines, 'comment')->textarea(['rows' => 3]) ?>

    </div>
    <div class="col-md-6">

        <?= $form->field($debtFines, 'regulation')->widget(\yii\widgets\MaskedInput::class, [
            'mask' => '99999999999999999999',
            'options' => [
                'placeholder' => 'Введите № постановления 20 символов'
            ],
        ]) ?>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($debtFines, 'geo_dtp')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($debtFines, 'stringDateDtp')->widget(\yii\jui\DatePicker::classname(), [
                    'language' => 'ru',
                    'dateFormat' => 'yyyy-MM-dd',
                    'options' => [
                        'placeholder' => Yii::$app->formatter->asDate(
                            (!empty($debtFines->date_dtp))? $debtFines->date_dtp : time(), "yyyy-MM-dd"),
                        'class'=> 'form-control',
                        'autocomplete'=>'off'
                    ],
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
                <?= $form->field($debtFines, 'payable')->textInput() ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($debtFines, 'stringDatePay')->widget(\yii\jui\DatePicker::classname(), [
                    'language' => 'ru',
                    'dateFormat' => 'yyyy-MM-dd',
                    'options' => [
                        'placeholder' => Yii::$app->formatter->asDate(
                            (!empty($debtFines->date_pay))? $debtFines->date_pay : time(), "yyyy-MM-dd"),
                        'class'=> 'form-control',
                        'autocomplete'=>'off'
                    ],
                    'clientOptions' => [
                        'changeMonth' => true,
                        'changeYear' => true,
                        'yearRange' => '2020:2050',
                    ]
                ]) ?>
            </div>
        </div>

    </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
    <?= $form->field($debtFines, 'driver_id')->textInput() ?>

    <?= $form->field($debtFines, 'car_id')->textInput() ?>

    <?= $form->field($debtFines, 'reason')->textInput() ?>

    <?php ActiveForm::end(); ?>

</div>


<?php
$js = <<< JS

    function verifyData(value, message)
    {
        return Boolean(value)?  value : alert(message);
    }
    
    function findDriverData()
    {
        let date = new Date(verifyData($("#debtfines-stringdatereason").val(), 'Введите дату.'))/1000;
        console.log(date);
        let carId = verifyData($("#debtfines-car_id").val(), 'Выберите автомобиль');
        console.log(carId);
        data = {
            date: date,
            carId: carId
        }
        sendAjax('search-driver-by-date-and-car',data);
    }
    
    function sendAjax(url,data)
    {
         $.ajax({
        url: url,
        type: 'POST',
        data: data,
        async: true,
        dataType: 'json',
        // beforeSend: function() {
        //     $('#loader').show();
        // },
        success: function(msg){
            console.log(msg);
            $(".modal-title").text("Найденные Данные:");
            if(msg.length >= 1 ){
                let html='<table class="table">'+
                    '  <thead class="thead-light">' +
                    '    <tr>' +
                    '      <th scope="col">Имя</th>' +
                    '      <th scope="col"></th>' +
                    '    </tr>' +
                    '  </thead>' +
                    '  <tbody>';
                msg.forEach(function(item, i, arr) {

                    html += '<tr>' +
                    '      <td>'+arr[i].last_name+' '+arr[i].first_name+'</td>' +
                    '      <td><button class="btn btn-primary getDriver" ' +
                        'data-fname="'+arr[i].first_name+'" ' +
                        'data-lname="'+arr[i].last_name+'" ' +
                        'data-id="'+arr[i].id+'"' +
                        '" >Выбрать</button></td>' +
                    '    </tr>';
                });
                html += '</tbody>'+
                    '</table>';
                $('.modal-body').html(html)

                $('.modal-body').on('click','.getDriver', function (){
                    $("#driver-first_name").val($(this).data('fname'));
                    $("#driver-last_name").val($(this).data('lname'));
                    $("#driver-yandex_id").val($(this).data('id'));
                    $("#driver-phone").val($(this).data('phone'));
                    $("#driver-driving_license").val($(this).data('dlicense'));

                    $('#exampleModalCenter').modal('hide');
                });

                $('#exampleModalCenter').modal('show');
            }else{
                alert("Ничего не найдено!")
            }
            
            // $('#loader').hide();
        }
    }); //ajax
    }
    
    $(".debt-fines-form").on("click", '#findDriver', findDriverData);
JS;

$this->registerJs( $js, $position = yii\web\View::POS_READY, $key = null );
?>​