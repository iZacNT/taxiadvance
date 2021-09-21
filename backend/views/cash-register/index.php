<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\jui\DatePicker;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\CashRegisterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Касса';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cash-register-index">

    <p>
        <?= Html::a('Добавить запись', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::button('Закрыть смену', ['class' => 'btn btn-warning closeCashRegistry']) ?>
    </p>

    <div class="row">
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
                <div class="inner">
                    <h3><?= Yii::$app->formatter->asCurrency($cashRegistry);?></h3>

                    <p>В кассе. </p>
                </div>
                <div class="icon">
                    <i class="fas fa-cash-register"></i>
                </div>
            </div>
        </div>
        <!-- ./col -->
    </div>

    <?php Pjax::begin([
            'id' => 'cashRegistryPjax'
    ]); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function($model) {
            if($model->isCloseCashRegistry()) {
                return ['style' => 'background: #6ddbbb;']; // Генерирует опции для tr
            }
            return [];
        },
        'columns' => [

            [
                'attribute' => 'type_cash',
                'filter' => Html::activeDropDownList($searchModel, 'type_cash',$searchModel->getTypeCash() ,['class'=>'form-control','prompt' => 'Выбирите тип']),
                'format' => 'raw',
                'value' => function($data){
                    if (\backend\models\User::isSuperUser()) {
                        return Html::a($data->getTypeCash()[$data->type_cash], ['view', 'id' => $data->id],['style' => 'font-size: 18px; font-weight:bold'])."<br>".Html::a("Редактировать", ['update', 'id' => $data->id],['class' => 'text-green']);
                    }
                    return Html::a($data->getTypeCash()[$data->type_cash], ['view', 'id' => $data->id],['style' => 'font-size: 18px; font-weight:bold']);
                }
            ],
            'cash:currency',
            'comment',
            [
                'attribute' => 'date_time',
                'filter' => DatePicker::widget([
                                        'model' => $searchModel,
                                        'attribute' => 'date_time',
                    'options' => [
                        'placeholder' => Yii::$app->formatter->asDate(($searchModel->date_time)? $searchModel->date_time : time(), "yyyy-MM-dd"),
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
                            ]),
                'format' => 'datetime'
           ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}'
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>

<?php
$js = <<< JS
let cashRegister = $cashRegistry;
JS;

$this->registerJs( $js, $position = yii\web\View::POS_END, $key = null );

$this->registerJsFile('@web/js/cash-registry/cashRegistryService.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>​