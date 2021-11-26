<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Parts */

$this->title = 'Добавить деталь';
$this->params['breadcrumbs'][] = ['label' => 'Детали', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parts-create">

    <?= $this->render('_form', [
        'model' => $model,
        'carMarks' => $carMarks,
    ]) ?>

</div>
