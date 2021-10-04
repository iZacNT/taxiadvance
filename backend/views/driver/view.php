<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model backend\models\Driver */

$this->title = $model->getFullName();
$this->params['breadcrumbs'][] = ['label' => 'Водители', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="driver-view">

    <p>
        <?= Html::a('Добавить водителя', ['create'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Все водители', ['index'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="col-12 col-sm-12">
        <div class="card card-primary card-tabs">
            <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-tabs-one-information-tab" data-toggle="pill" href="#custom-tabs-one-information" role="tab" aria-controls="custom-tabs-one-information" aria-selected="true">Информация о Водителе</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-one-deposit-tab" data-toggle="pill" href="#custom-tabs-one-deposit" role="tab" aria-controls="custom-tabs-one-deposit" aria-selected="false">Депозит: <?= \Yii::$app->formatter->asCurrency($summDeposit);?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-one-debt-tab" data-toggle="pill" href="#custom-tabs-one-debt" role="tab" aria-controls="custom-tabs-one-debt" aria-selected="false">Долги: <?= \Yii::$app->formatter->asCurrency($summDebt);?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-one-orders-tab" data-toggle="pill" href="#custom-tabs-one-orders" role="tab" aria-controls="custom-tabs-one-orders" aria-selected="false">Заказы: <?= \Yii::$app->formatter->asCurrency($summOrders);?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-one-calculations-tab" data-toggle="pill" href="#custom-tabs-one-calculations" role="tab" aria-controls="custom-tabs-one-calculations" aria-selected="false">РАСЧЕТ ТЕКУЩЕЙ СМЕНЫ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-one-all-shifts-tab" data-toggle="pill" href="#custom-tabs-one-all-shifts" role="tab" aria-controls="custom-tabs-one-all-shifts" aria-selected="false">Все смены</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-one-tabContent">
                    <div class="tab-pane fade show active" id="custom-tabs-one-information" role="tabpanel" aria-labelledby="custom-tabs-one-information-tab">

                        <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                [
                                    'attribute' => 'fullName',
                                    'label' => 'ФИО',
                                    'format' => 'raw',
                                    'value' => function($data){
                                        return $data->getFullName();
                                    }
                                ],
                                'phone',
                                'filialData.name',
                                [
                                    'attribute' => 'status',
                                    'value' => function($data){
                                        return $data->statusDriver[$data->status];
                                    },
                                ],
                                'yandex_id',
                                'shift_closing:datetime',
                                'driving_license',
                                'passport',
                                'date_of_issue:date',
                                'who_issued_it',
                                [
                                    'attribute' => 'adress',
                                    'label' => 'Адрес',
                                    'value' => $model->getAdress()
                                ],
                                'commens:ntext',


                            ],
                        ]) ?>

                    </div>
                    <div class="tab-pane fade" id="custom-tabs-one-deposit" role="tabpanel" aria-labelledby="custom-tabs-one-deposit-tab">

                        <?= Html::a('Добавить депозит', ['deposit/create', 'idDriver' => $model->id], ['class' => 'btn btn-primary mb-3']) ?>

                        <?php Pjax::begin([
                                'id' => "depositDriver"
                        ]); ?>
                        <?php
                            echo GridView::widget([
                            'dataProvider' => $depositDataProvider,
                                'columns' => [
                                    'id',
                                    'contributed:currency',
                                    'gave:currency',
                                    'created_at:date',
                                    'comment:ntext',
                                    ['class' => \yii\grid\ActionColumn::className(),
                                        'controller' => 'deposit',
                                        'template' => '{update} &nbsp;&nbsp;{delete}']
                                ],
                            ]);
                       ?>
                        <?php Pjax::end(); ?>

                    </div>
                    <div class="tab-pane fade" id="custom-tabs-one-debt" role="tabpanel" aria-labelledby="custom-tabs-one-debt-tab">
                        <?= Html::a('Добавить Долг', ['debt/create', 'idDriver' => $model->id], ['class' => 'btn btn-primary mb-3']) ?>

                        <?php Pjax::begin([
                            'id' => "debtDriver"
                        ]); ?>
                        <?php
                        echo GridView::widget([
                            'dataProvider' => $debtDataProvider,
                            'columns' => [
                                'id',
                                'dette:currency',
                                'back:currency',
                                'date_reason:date',
                                [
                                    'attribute' => 'reason',
                                    'format' => 'raw',
                                    'value' => function($data){
                                            return  $data->debtReasons[$data->reason];
                                    }
                                ],
                                'car.fullNameMark',
                                'comment:ntext',
                                ['class' => \yii\grid\ActionColumn::className(),
                                    'controller' => 'debt',
                                    'template' => '{update} &nbsp;&nbsp;{delete}']
                            ],
                        ]);
                        ?>
                        <?php Pjax::end(); ?>
                    </div>
                    <div class="tab-pane fade" id="custom-tabs-one-orders" role="tabpanel" aria-labelledby="custom-tabs-one-orders-tab">
                            <?php Pjax::begin([
                                'id' => "allOrders"
                            ]); ?>
                            <?php
                            echo GridView::widget([
                                'dataProvider' => $allOreders,
                                'options'=>['style' => 'white-space:nowrap;'],
                                'columns' => [
                                    [
                                            'attribute' => 'address_from.address',
                                        'label' => 'Адрес заказа',
                                        'contentOptions' => ['style' => 'width:150px;  min-width:150px;'],
                                    ],
                                    [
                                        'attribute' => 'booked_at',
                                        'label' => 'Адрес заказа',
                                        'format' => 'datetime'
                                    ],
//                                    [
//                                        'attribute' => 'category',
//                                        'label' => 'Категория'
//                                    ],
                                    [
                                        'attribute' => 'price',
                                        'label' => 'Цена',
                                        'format' => 'currency'
                                    ],
                                    [
                                        'attribute' => 'payment_method',
                                        'label' => 'Оплата',
                                        'value' => function($data){
                                            $result = [
                                                'cash' => 'наличные',
                                            'cashless' => 'безнал',
                                            'card' => 'карта',
                                            'internal' => 'внутренний',
                                            'other' => 'другое',
                                            'corp' => 'корп. счёт',
                                            'prepaid' => 'предоплата'
                                            ];
                                            return ($result[$data['payment_method']]) ? $result[$data['payment_method']] : $data['payment_method'];
                                        }
                                    ],
                                    [
                                        'attribute' => 'status',
                                        'label' => 'Статус',
                                        'value' => function($data){
                                            $result = [

                                                'none' => 'без статуса',
                                                'driving' => 'в пути',
                                                'waiting' => 'ждёт клиента',
                                                'transporting' => 'везёт клиента',
                                                'complete' => 'выполнен',
                                                'cancelled' => 'отменён',
                                                'calling' => 'ошибка, технический статус',
                                                'expired' => 'ошибка, технический статус',
                                                'failed' => 'ошибка, технический статус'

                                            ];
                                            return ($result[$data['status']]) ? $result[$data['status']] : $data['status'];
                                        }
                                    ],
//                                    'car.brand_model',
//                                    'license.number',
                                    ],
                            ]);
                            ?>
                            <?php Pjax::end(); ?>
                    </div>
                    <div class="tab-pane fade" id="custom-tabs-one-calculations" role="tabpanel" aria-labelledby="custom-tabs-one-calculations-tab">

                        <?php Pjax::begin([
                            'id' => "calculations"
                        ]); ?>
                        <div class="row">
                        <div class="col-md-6">
                            <table id="example2" class="table table-bordered table-hover dataTable dtr-inline" role="grid" aria-describedby="example2_info">
                                <thead>
                                <tr role="row">
                                    <th class="sorting sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">Наименование</th>
                                    <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending">Сумма</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <tr class="odd">
                                    <td class="dtr-control sorting_1" tabindex="0">Заказы с начала смены:</td>
                                    <td><?= \Yii::$app->formatter->asCurrency($summOrders);?></td>
                                </tr>
                                <tr class="odd">
                                    <td class="dtr-control sorting_1" tabindex="0">План: <span id="planSumm"><?= \Yii::$app->formatter->asCurrency($plan);?></span></td>
                                    <td><?=
                                            Html::textInput('fromSummOrders', $summOrders, ['class' => 'form-control', 'id' => 'fromSummOrders'] )
                                        ?></td>
                                </tr>
                                <tr class="odd">
                                    <td class="dtr-control sorting_1" tabindex="0">Баланс на Яндекс:</td>
                                    <td><?= $balanceYandex;?></td>
                                </tr>
                                <tr class="odd">
                                    <td class="dtr-control sorting_1" tabindex="0">Бонусы на Яндекс:</td>
                                    <td><?= $bonus;?></td>
                                </tr>
                                <tr class="odd">
                                    <td class="dtr-control sorting_1" tabindex="0">Депо:</td>
                                    <td><?= $depo;?></td>
                                </tr>
                                <tr class="odd">
                                    <td class="dtr-control sorting_1" tabindex="0">Долг по смене:</td>
                                    <td>
                                       <?= Html::textInput('debtFromShift', 0, ['class' => 'form-control', 'id' => 'debtFromShift'] ); ?>
                                    </td>
                                </tr>
                                <tr class="odd">
                                    <td class="dtr-control sorting_1" tabindex="0">Мойка:</td>
                                    <td>
                                        <?= Html::textInput('carWash', 0, ['class' => 'form-control', 'id' => 'carWash'] ); ?>
                                    </td>
                                </tr>
                                <tr class="odd">
                                    <td class="dtr-control sorting_1" tabindex="0"><strong><?= $carFuel;?>:</strong> (<?= $car;?>)<br>
                                        № Карты: <?= $card?></td>
                                    <td>
                                        <?= Html::textInput('carFuel', 0, ['class' => 'form-control', 'id' => 'carFuel'] ); ?>
                                    </td>
                                </tr>
                                <tr class="odd">
                                    <td class="dtr-control sorting_1" tabindex="0">Телефон: <?= $phone;?></td>
                                    <td>
                                        <?= Html::textInput('carPhone', 0, ['class' => 'form-control', 'id' => 'carPhone'] ); ?>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                                    <?= $generateTarifTable;?>
                            <div class="row resultData">
                                <div class="col-md-8" id="resultAjax">
                                </div>
                                <div class="col-md-4">
                                    <button type="button" id="saveDataButton" class="btn btn-success btn-lg" style="height: 80px; display: none;">Сохранить расчет</button>
                                </div>
                            </div>
                        </div>
                        </div>
                        <?php Pjax::end(); ?>
                    </div>
                    <div class="tab-pane fade" id="custom-tabs-one-all-shifts" role="tabpanel" aria-labelledby="custom-tabs-one-all-shifts-tab">

                        <?php Pjax::begin([
                            'id' => "allShifts"
                        ]); ?>
                        <?php
                        echo GridView::widget([
                            'dataProvider' => $allShiftDataProvider,
                            'columns' => [
                                'date_billing:date',
                                [
                                    'attribute' => 'type_day',
                                    'format' => 'raw',
                                    'value' => function($data){
                                        return \common\service\constants\Constants::getDayProperty()[$data->type_day];
                                    }
                                ],
                                [
                                    'attribute' => 'period',
                                    'format' => 'raw',
                                    'value' => function($data){
                                        return \common\service\constants\Constants::getPeriod()[$data->period];
                                    }
                                ],
                                'balance_yandex:currency',
                                'bonus_yandex:currency',
                                'input_amount:currency:Общаяя',
                                'depo',
                                'debt_from_shift:currency',
                                'car_wash:currency',
                                'car_fuel_summ:currency',
                                'car_phone_summ:currency',
                                'summ_driver:currency:Водителю',
                                'plan:currency'
                            ],
                        ]);
                        ?>
                        <?php Pjax::end(); ?>

                    </div>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>

</div>

<?php
$jsRaschet = <<< JS
let filial = $model->filial;
let balanceYandex = $balanceYandex;
let bonusYandex = $bonus;
let depo = $depo;
let driverId = $model->id;
JS;

$this->registerJs( $jsRaschet, $position = yii\web\View::POS_END);
$this->registerJsFile('@web/js/driver/calculateShift.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>​