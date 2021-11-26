<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Stock */

$this->title = 'Изменить запись: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Склад', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->partName, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="stock-update">

    <?= $this->render('_form', [
        'model' => $model,
        'carsParts' => $carsParts,
        'openRepairs' => $openRepairs,
    ]) ?>

</div>
