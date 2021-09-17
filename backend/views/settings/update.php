
<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Settings */

$this->title = 'Общие настройки: ';
$this->params['breadcrumbs'][] = ['label' => 'Общие настройки', 'url' => ['update']];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="settings-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
