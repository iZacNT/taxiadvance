<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Phones */

$this->title = 'Изменить телефо: ' . $model->mark;
$this->params['breadcrumbs'][] = ['label' => 'Телефоны', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->mark, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="phones-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
