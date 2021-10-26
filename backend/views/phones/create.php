<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Phones */

$this->title = 'Добавить телефон';
$this->params['breadcrumbs'][] = ['label' => 'Телефоны', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="phones-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
