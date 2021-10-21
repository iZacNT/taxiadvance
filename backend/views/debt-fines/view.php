<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $debtFines backend\models\DebtFines */

$this->title = $debtFines->driverInfo->fullName;
$this->params['breadcrumbs'][] = ['label' => 'Штрафы ДПС', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="debt-fines-view">

    <p>
        <?= Html::a('Добавить Штраф ДПС', ['create'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Все Штрафы ДПС', ['index'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Изменить', ['update', 'id' => $debtFines->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $debtFines->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $debtFines,
        'attributes' => [
            'id',
            'driverInfo.fullName',
            'dette:currency',
            'back:currency',
            ['attribute' => 'reason',
                'value' => function(){
                    return "Штраф ДПС";
                }],
            'comment:ntext',
            'carInfo.fullNameMark',
            'date_reason:date',
            'regulation',
            'geo_dtp',
            'date_dtp:date',
            'payable:currency',
            'date_pay:date',
        ],
    ]) ?>

</div>
