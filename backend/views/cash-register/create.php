<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CashRegister */

$this->title = 'Добавить приход/расход';
$this->params['breadcrumbs'][] = ['label' => 'Касса', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cash-register-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
