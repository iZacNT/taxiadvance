<?php

use common\service\constants\Constants;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\DriverBillingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Биллинг';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="driver-billing-index">

    <?php Pjax::begin(); ?>

    <?php echo $this->render('_search', ['model' => $searchModel, 'calculation' => $calculation]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
       // 'filterModel' => $searchModel,
        'columns' => [

                                    [
                                        'class' => \common\service\columns\VerifyColumn::className(),
                                        'contentOptions'=>['style'=>'text-align: center;'],
                                        'attribute' => 'verify',
                                        'format' => 'raw',
                                        'label' => '№ смены'
                                    ],
                                    [
                                        'attribute' => 'date_billing',
                                        'format' => 'raw',
                                        'value' => function($data){
                                            return "<strong>ID:</strong>".$data->id."&nbsp;&nbsp;&nbsp;&nbsp;<strong>"
                                                .$data->shiftBilling."</strong><br /><strong>Тип:</strong> ".
                                                Constants::getDayProperty()[$data->type_day]."<br /><strong>Период:</strong> ".
                                                Constants::getPeriod()[$data->period];
                                        }
                                    ],
                                [
                                    'attribute' => 'carDriver',
                                    'label' => 'Авто/Водитель',
                                    'format' => 'raw',
                                    'value' => function($data){
                                        $carMark = (!empty($data->carInfo->fullNameMark))? $data->carInfo->fullNameMark : "-";
                                        $rolling = (!empty($data->rolling)) ? $data->rolling : 0;
                                        return $data->driverInfo->fullName."<br>".$carMark."<br>Пробег: ".$rolling."км";
                                    }
                                ],

                                [
                                        'attribute' => 'input_amount',
                                    'label' => 'Общая',
                                    'format' => 'raw',
                                    'value' => function($data){
                                        return "<strong>Общая:</strong> ".Yii::$app->formatter->asCurrency($data->input_amount)."<br>".
                                            "&ensp;<span style='font-size: 14px; font-weight: lighter'>+ Бонус:</span> ".Yii::$app->formatter->asCurrency($data->input_amount+$data->bonus_yandex)."<br>".
                                            "<strong>План:</strong> ".Yii::$app->formatter->asCurrency($data->plan);
                                    }
                                ],
                                [
                                        'attribute' => 'balance_yandex',
                                    'format' => 'raw',
                                    'label' => 'Яндекс',
                                    'value' => function($data){
                            return "<strong>Баланс:</strong> ".Yii::$app->formatter->asCurrency($data['balance_yandex']).
                                "<br /><strong>Бонус:</strong> ".Yii::$app->formatter->asCurrency($data['bonus_yandex']);
                                    }
                                ],
                                [
                                    'attribute' => 'depo',
                                    'format' => 'raw',
                                    'label' => 'Допы',
                                    'value' => function($data){
                                        return "<strong>Топливо:</strong> ".Yii::$app->formatter->asCurrency($data['car_fuel_summ']).
                                            "<br /><strong>Телефон:</strong> ".Yii::$app->formatter->asCurrency($data['car_phone_summ']).
                                            "<br /><strong>Депо: </strong>".Yii::$app->formatter->asCurrency($data['depo']);
                                    }
                                ],
//                                'debt_from_shift:currency',
//                                'car_wash:currency',
//                                'summ_driver:currency:Водителю',
                                [
                                    'attribute' => 'billing',
                                    'format' => 'raw',
                                    'label' => 'Выручка',
                                    'value' => function($data){
                                        return "<span style='font-size: 14px; font-weight: lighter'>&sum; Парка: ".Yii::$app->formatter->asCurrency($data->sumWithAdditionally['sum_park']).
                                            "<br />+ Допы: ".Yii::$app->formatter->asCurrency($data->sumWithAdditionally['additional']).
                                            "</span><br /><strong>Итого: </strong>".Yii::$app->formatter->asCurrency($data['billing']);
                                    }
                                ],
                                ['class' => 'yii\grid\ActionColumn'],

                            ],
        'pager' => [
            'maxButtonCount' => 10,
            'options' => ['class' => 'pagination pagination-sm'],
            'linkContainerOptions' => ['class' => 'page-item'],
            'linkOptions' => ['class' => 'page-link']
        ],

    ]); ?>

    <?php Pjax::end(); ?>

</div>
