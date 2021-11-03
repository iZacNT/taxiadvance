<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CarSharing */

$this->title = 'Изменить аренду от: ' . Yii::$app->formatter->asDate($model->date_stop);
$this->params['breadcrumbs'][] = ['label' => 'Аренда Автомобилей', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="car-sharing-update">

    <?= $this->render('_form', [
        'model' => $model,
        'drivers' => $drivers
    ]) ?>

</div>
