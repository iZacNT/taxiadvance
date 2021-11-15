<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ManagerForm */

$this->title = 'Изменить: ' . $managerForm->id;
$this->params['breadcrumbs'][] = ['label' => 'Менеджеры/Операторы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $managerForm->id, 'url' => ['view', 'id' => $managerForm->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="manager-update">

    <?= $this->render('_form', [
        'managerForm' => $managerForm,
    ]) ?>

</div>
