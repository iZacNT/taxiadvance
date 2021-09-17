<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CashRegister */

$this->title = 'Изменить приход/расход: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Касса', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->getTypeCash()[$model->type_cash], 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="cash-register-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
