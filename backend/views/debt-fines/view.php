<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\DebtFines */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Debt Fines', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="debt-fines-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
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
            'driver_id',
            'dette',
            'back',
            'reason',
            'comment:ntext',
            'car_id',
            'date_reason',
            'regulation',
            'geo_dtp',
            'date_dtp',
            'payable',
            'date_pay',
        ],
    ]) ?>

</div>
