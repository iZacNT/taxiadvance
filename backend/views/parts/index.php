<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\PartsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Детали';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parts-index">

    <p>
        <?= Html::a('Добавить деталь', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                    'attribute' => 'name_part',
                'format' => 'raw',
                'value' => function($data){
                    return Html::a($data->name_part,['parts/update', 'id' => $data->id], ['style' => 'font-size:18px; font-weight: bold']);
                }
            ],
            'mark',
            'sumPartsOnStock:raw:Количество',
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
