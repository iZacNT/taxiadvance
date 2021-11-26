<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\CarRepairs */

$this->title = 'Create Car Repairs';
$this->params['breadcrumbs'][] = ['label' => 'Car Repairs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="car-repairs-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'cars' => $cars
    ]) ?>

</div>
