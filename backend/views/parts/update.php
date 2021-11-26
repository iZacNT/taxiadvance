<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Parts */

$this->title = 'Изменить деталь: ' . $model->name_part;
$this->params['breadcrumbs'][] = ['label' => 'Детали', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name_part, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="parts-update">

    <?= $this->render('_form', [
        'model' => $model,
        'carMarks' => $carMarks,
    ]) ?>

</div>
