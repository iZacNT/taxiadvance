<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\DriverBilling */

$this->title = 'Расчет по смене: ' . $model->shift_id." от ".Yii::$app->formatter->asDate($model->driverTabelShift->work_date);
$this->params['breadcrumbs'][] = ['label' => 'Расчеты', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="driver-billing-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

$this->registerJsFile('@web/js/driverBilling/recalculation.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>​