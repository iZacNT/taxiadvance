<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\CarRepairs */

$this->title = 'Изменить: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Ремонты', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="car-repairs-update">

    <?= $this->render('_form', [
        'model' => $model,
        'cars' => $cars
    ]) ?>

</div>
