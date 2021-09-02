<?php

/* @var $this \yii\web\View */
/* @var $deposit \backend\models\Deposit */

$this->title = 'Добавить депозит';
$this->params['breadcrumbs'][] = ['label' => 'Депозиты', 'url' => ['driver/view', 'id' => $idDriver]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="deposit-create">
        <?= $this->render('_form', [
            'deposit' => $deposit,
            'idDriver' => $idDriver
        ]) ?>
</div>


