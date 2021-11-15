<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ManagerForm */

$this->title = 'Добавить';
$this->params['breadcrumbs'][] = ['label' => 'Менеджеры/Операторы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="manager-create">

    <?= $this->render('_form', [
        'managerForm' => $managerForm,
    ]) ?>

</div>
