<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Stock */

$this->title = 'Добавить';
$this->params['breadcrumbs'][] = ['label' => 'Склад', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stock-create">

    <?= $this->render('_form', [
        'model' => $model,
        'carsParts' => $carsParts,
        'openRepairs' => $openRepairs
    ]) ?>

</div>
