<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\CarSharing */

$this->title = 'Сдать автомобиль в аренду';
$this->params['breadcrumbs'][] = ['label' => 'Аренда', 'url' => ['cars/view', 'id' => $model->car_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="car-sharing-create">

    <?= $this->render('_form', [
        'model' => $model,
        'drivers' => $drivers
    ]) ?>

</div>
