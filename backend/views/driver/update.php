<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Driver */

$this->title = 'Изменить водителя: ' . $model->getFullName();
$this->params['breadcrumbs'][] = ['label' => 'Водители', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->getFullName(), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="driver-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
