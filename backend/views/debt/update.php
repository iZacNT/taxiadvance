<?php

/* @var $this \yii\web\View */
/* @var $debt \backend\models\Debt|array|null|\yii\db\ActiveRecord */

use yii\helpers\Html;

$this->title = 'Изменить долг: ';
$this->params['breadcrumbs'][] = ['label' => 'Долги', 'url' => ['driver/view', 'id' => $debt->driver_id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
    <div class="driver-update">

        <?= $this->render('_form', [
            'debt' => $debt,
            'cars' => $cars
        ]) ?>

    </div>
<?php




