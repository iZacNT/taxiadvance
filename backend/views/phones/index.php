<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\PhonesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Телефоны';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="phones-index">

    <p>
        <?= Html::a('Добавить телефон', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'phone_id',
            'emei',
            'mark',
            'sim_number',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
