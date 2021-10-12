<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\CarsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Автомобили';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cars-index">

    <p>
        <?= Html::a('Добавить автомобиль', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'fullNameMark',
                'format' => 'raw',
                'value' => function($data){
                    return Html::a($data->getFullNameMark(), ['view', 'id' => $data->id],['style' => 'font-size:20px; font-weight:bold;'])."<br>".Html::a("Редактировать", ['update', 'id' => $data->id],['class' => 'text-green']);
                }
            ],
            'vin',
            //'status',
            //'comment',
            //'name_insurance',
            //'date_osago',
            //'date_dosago',
            //'date_kasko',
            [
                'attribute' => 'name_owner',
                'filter' => $searchModel->getAllOwner(),
                'format' => 'raw',
                'value' => 'name_owner'
            ],
            //'fuel',
            [
                'attribute' => 'filial',
                'filter' => (new \backend\models\Filials())->getAllFilials(),
                'format' => 'raw',
                'value' => 'filialData.name'
            ],

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{delete}'
            ],
        ],
        'pager' => [
            'maxButtonCount' => 5,
            'options' => ['class' => 'pagination pagination-sm'],
            'linkContainerOptions' => ['class' => 'page-item'],
            'linkOptions' => ['class' => 'page-link']
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
