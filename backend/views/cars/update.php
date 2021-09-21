<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Cars */

$this->title = 'Изменить авто: ' . $model->getFullNameMark();
$this->params['breadcrumbs'][] = ['label' => 'Автомобили', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->getFullNameMark(), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="cars-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
