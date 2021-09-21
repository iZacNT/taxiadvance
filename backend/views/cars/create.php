<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Cars */

$this->title = 'Добавить автомобиль';
$this->params['breadcrumbs'][] = ['label' => 'Автомобили', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cars-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
