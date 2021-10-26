<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $driverTabel app\models\DriverTabel */

$this->title = $driverTabel->carMark." ". Yii::$app->formatter->asDate($driverTabel->work_date);
$this->params['breadcrumbs'][] = ['label' => 'Табель', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="driver-tabel-view">

    <p>
        <?= Html::a('Добавить смену', ['create'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Табель', ['index'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Изменить', ['update', 'id' => $driverTabel->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $driverTabel->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $driverTabel,
        'attributes' => [
            'carMark',
            'work_date:date',
            'fullDayDriverName.fullName:raw:Дневная смена',
            'sum_card_day:currency',
            'fullPhoneDayInfo.fullInfo:raw:Информация о телефоне',
            'sum_phone_day:currency',
            'fullNightDriverName.fullName:raw:Ночная смена',
            'sum_card_night:currency',
            'fullPhoneNightInfo.fullInfo:raw:Информация о телефоне',
            'sum_phone_night:currency'
        ],
    ]) ?>

</div>
