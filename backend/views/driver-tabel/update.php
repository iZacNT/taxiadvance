<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DriverTabel */

$this->title = $driverTabel->carMark." ".Yii::$app->formatter->asDate($driverTabel->work_date, 'yyyy-MM-dd');
$this->params['breadcrumbs'][] = ['label' => 'Табель', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['view', 'id' => $driverTabel->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="driver-tabel-update">

    <?= $this->render('_form', [
        'driverTabel' => $driverTabel,
        'cars' => $cars,
        'drivers' => $drivers
    ]) ?>

</div>
