<?php


/* @var $this yii\web\View */
/* @var $model backend\models\DriverTabel */

$this->title = 'Добавить смену';
$this->params['breadcrumbs'][] = ['label' => 'Табель', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="driver-tabel-create">

    <?= $this->render('_form', [
        'driverTabel' => $driverTabel,
        'cars' => $cars,
        'drivers' => $drivers,
        'freePhones' => $freePhones
    ]) ?>

</div>
