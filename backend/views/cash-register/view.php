<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\CashRegister */

$this->title = $model->getTypeCash()[$model->type_cash];;
$this->params['breadcrumbs'][] = ['label' => 'Касса', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="cash-register-view col-md-6">

    <p>
        <?= Html::a('Добавить запись', ['create'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Все записи', ['index'], ['class' => 'btn btn-success']) ?>
        <?  if (\backend\models\User::isSuperUser()) {?>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <? } ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'type_cash',
                'format' => 'raw',
                'value' => function($data){
                    return $data->getTypeCash()[$data->type_cash];
                }
            ],
            'cash',
            'comment',
        ],
    ]) ?>

</div>
