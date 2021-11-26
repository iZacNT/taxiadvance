<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\CarRepairsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ремонт Авто';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="car-repairs-index">

    <p>
        <?= Html::a('Добавить ремонт', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            [
                'attribute' => 'id',
                'contentOptions' => ['style' => 'width:100px;'],
            ],
            [
                    'attribute' => 'carFullName',
                'format' => 'raw',
                'value' => function($data){
                    return Html::a($data->carFullName,['car-repairs/view', 'id' => $data->id], ['style' => 'font-size: 20px; font-weight: bold;'])
                            ."<br>"
                        .Html::a('Редактировать',['car-repairs/update', 'id' => $data->id], ['class' => 'text-green']);
                }
            ],
            'date_open_repair:datetime',
            'date_close_repare:datetime',
            [
                'attribute' => 'status',
                'filter'    => Html::activeDropDownList($searchModel, 'status', $searchModel->statusLabel, ['prompt' => 'Выберите статус', 'class' => 'form-control']),
                'value' => 'statusType'
            ],

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{delete}'
                ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
