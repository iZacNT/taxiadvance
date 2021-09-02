<?php

use yii\helpers\Html;

$this->title = 'Изменить депозит: ';
$this->params['breadcrumbs'][] = ['label' => 'Депозиты', 'url' => ['driver/view', 'id' => $deposit->driver_id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
    <div class="driver-update">

        <?= $this->render('_form', [
            'deposit' => $deposit,
        ]) ?>

    </div>
