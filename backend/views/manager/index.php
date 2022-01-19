<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\ManagerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Менеджеры/Операторы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="manager-index">

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
       //    'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'first_name',
            'last_name',
            'phone',
            'user.email:raw:E-mail',
            [
                'attribute' => 'status',
                'label' => 'Статус',
                'value' => function($data){
                    return $data->statusArray[$data->user->status];
                }
            ],
            [
                'attribute' => 'role',
                'label' => 'Роль    ',
                'value' => function($data){
                    return $data->roleArray[$data->user->role];
                }
            ],
            //'filial',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
