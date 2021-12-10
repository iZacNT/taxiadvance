<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\DriverBilling */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Driver Billings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="driver-billing-view">

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
            'type_day',
            'plan',
            'summ_driver',
            'summ_park',
            'percent_driver',
            'percent_park',
            'billing',
            'hours',
            'car_phone_summ',
            'car_fuel_summ',
            'car_wash',
            'debt_from_shift',
            'depo',
            'input_amount',
            'period',
            'fuel',
            'car_mark',
            'bonus_yandex',
            'balance_yandex',
            'date_billing',
            'driver_id',
            'id',
            'compensations',
            'car_id',
            'shift_id',
            'verify',
        ],
    ]) ?>

</div>
