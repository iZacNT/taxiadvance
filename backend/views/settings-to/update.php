<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\SettingsTo */

$this->title = 'Настройки Технического осмотра';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['update', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
