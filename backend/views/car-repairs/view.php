<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\CarRepairs */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Ремонты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="car-repairs-view">

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'car_id',
                'format' => 'raw',
                'value' => function($data){
                    return Html::a($data->carFullName, ['cars/view', 'id' => $data->car_id]);
                }
            ],
            'date_open_repair:datetime',
            'date_close_repare:datetime',
            'statusType'
        ],
    ]) ?>

</div>
