<?php

use kartik\datetime\DateTimePicker;
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
            <div class="col-md-5">

                <?php
                echo $form->field($debtFines, 'stringDateReason')->widget(DateTimePicker::classname(), [
                    'options' => ['placeholder' => 'Enter event time ...'],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd hh:ss'
                    ]
                ]);
                ?>
<!--                --><?//= $form->field($debtFines, 'stringDateReason')->widget(\yii\jui\DatePicker::classname(), [
//                    'language' => 'ru',
//                    'dateFormat' => 'yyyy-MM-dd',
//                    'options' => [
//                        'placeholder' => Yii::$app->formatter->asDate(
//                            (!empty($debtFines->date_reason))? $debtFines->date_reason : time(), "yyyy-MM-dd"
//                        ),
//                        'class'=> 'form-control',
//                    ],
//                    'clientOptions' => [
//                        'changeMonth' => true,
//                        'changeYear' => true,
//                        'yearRange' => '2020:2050',
//                    ],
//                ]) ?>
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
            <div class="col-md-3">
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

                    ])->label("Водитель");
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
    <?= $form->field($debtFines, 'driver_id')->hiddenInput()->label("") ?>

    <?= $form->field($debtFines, 'car_id')->hiddenInput()->label("") ?>

    <?= $form->field($debtFines, 'reason')->hiddenInput()->label("") ?>

    <?php ActiveForm::end(); ?>

</div>

<?php

$js = <<< JS

    function verifyData(value, message)
    {
        var splitString = value.split(' ');
        return Boolean(splitString[0])?  splitString[0] : alert(message);
    }
    
    function findDriverData()
    {
        let dateVerify = verifyData($("#debtfines-stringdatereason").val(), 'Введите дату.');
        console.log(dateVerify)
        if (Boolean(dateVerify)){
        let date = new Date(dateVerify)/1000;
        console.log("..."+date);
        let carId = verifyData($("#debtfines-car_id").val(), 'Выберите автомобиль');
        console.log(carId);
        data = {
            date: date,
            carId: carId
        }
        sendAjax('search-driver-by-date-and-car',data);
        }
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
            $('#modalSave').hide();
            $(".modal-title").text("Найденные Данные: "+msg.car_id+' от '+msg.date);
            if(msg.length >= 1 ){
                let html = '<div class="row">' +
                prepareData(msg)+
                 '</div>';
                $('.modal-body').html(html)
                $('.modal-body').on('click','.selectDriver', function (){
                    $("#autocompleteDriver").val($(this).data('fullname'));
                    $("#debtfines-driver_id").val($(this).data('id'));
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
    
    function prepareData(msg)
    {
        let disabledDay = '';
        let disabledNight = '';
        
        if (msg.driver_day_id == null){ disabledDay = 'disabled'}
        if (msg.driver_night_id == null){ disabledNight = 'disabled'}
        let html = '<div class="col-md-6">'+
            '<div class="card">'+
              '<div class="card-header">'+
                '<h3 class="card-title">'+
                  'Дневная смена: '+
                '</h3>'+
              '</div>'+
              <!-- /.card-header -->
              '<div class="card-body">'+
                '<dl>'+
                  '<dt>Водитель: </dt>'+
                  '<dd>'+msg.fullname_driver_day+'</dd>'+
                  '<dt>Сумма за бензин: </dt>'+
                  '<dd>'+msg.driver_sum_card_day+'</dd>'+
                  '<dt>Телефон: </dt>'+
                  '<dd>'+msg.driver_sum_card_day+'</dd>'+
                  '<dt>Статус смены: </dt>'+
                  '<dd>'+msg.driver_status_day_shift+'</dd>'+
                  '<dt>Время закрытия смены: </dt>'+
                  '<dd>'+msg.date_close_day_shift+'</dd>'+
                  '<button class="btn btn-primary selectDriver" style="width: 100%"' +
                   'data-id="'+msg.driver_day_id+'"' +
                    ' data-fullname="'+msg.fullname_driver_day+'" '+disabledDay+'>Выбрать</button>'+
                '</dl>'+
              '</div>'+
              <!-- /.card-body -->
            '</div>'+
            <!-- /.card -->
          '</div>';
        
        html += '<div class="col-md-6">'+
            '<div class="card">'+
              '<div class="card-header">'+
                '<h3 class="card-title">'+
                  'Ночная смена: '+
                '</h3>'+
              '</div>'+
              <!-- /.card-header -->
              '<div class="card-body">'+
                '<dl>'+
                  '<dt>Водитель: </dt>'+
                  '<dd>'+msg.fullname_driver_night+'</dd>'+
                  '<dt>Сумма за бензин: </dt>'+
                  '<dd>'+msg.driver_sum_card_night+'</dd>'+
                  '<dt>Телефон: </dt>'+
                  '<dd>'+msg.driver_sum_card_night+'</dd>'+
                  '<dt>Статус смены: </dt>'+
                  '<dd>'+msg.driver_status_night_shift+'</dd>'+
                  '<dt>Время закрытия смены: </dt>'+
                  '<dd>'+msg.date_close_night_shift+'</dd>'+
                  '<button class="btn btn-primary selectDriver" style="width: 100%"' +
                   'data-id="'+msg.driver_night_id+'"' +
                    ' data-fullname="'+msg.fullname_driver_night+'" '+disabledNight+'>Выбрать</button>'+
                '</dl>'+
              '</div>'+
              <!-- /.card-body -->
            '</div>'+
            <!-- /.card -->
          '</div>';
        return html;
    }
    
    $(".debt-fines-form").on("click", '#findDriver', findDriverData);
JS;

$this->registerJs( $js, $position = yii\web\View::POS_READY, $key = null );
?>​