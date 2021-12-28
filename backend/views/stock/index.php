<?php

use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\StockSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Склад';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stock-index">

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                    'attribute' => 'partName',
                'format' => 'raw',
                'value' => function($data){
                    return Html::a($data->partName,['stock/view', 'id' => $data->id],['style' => 'font-size:20px; font-weight: bold'])
                        ."<br>"
                        .Html::a('Редактировать',['stock/update', 'id' => $data->id],['class' => 'text-green']);
                }
            ],
            'count',
            [
                'attribute' => 'type',
                'filter' => Html::activeDropDownList($searchModel, 'type',$searchModel->typeStock ,['class'=>'form-control','prompt' => 'Выбирите тип']),
                'value' => function($data){
                    return $data->typeStock[$data->type];
                }
            ],
            'invoice',
            'repair_id',
            [
                'attribute' => 'date',
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'dateString',
                    'options' => ['placeholder' => 'Введите дату'],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'dd-mm-yyyy'
                    ]
                ]),
                'format' => 'datetime'
            ],

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{delete}'
                ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
