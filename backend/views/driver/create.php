<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Driver */

$this->title = 'Добавить водителя';
$this->params['breadcrumbs'][] = ['label' => 'Водители', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="driver-create">
    <div class="row">
        <div class="col-md-6">
            <div id="searchDriver" class="card">
                <div class="card-header">
                    <h3 class="card-title">Найти водителя в Яндекс</h3>
                    <h3 id="loader" class="card-title float-right" style="display: none;"><i class="fas fa-2x fa-sync-alt fa-spin" style="font-size: 18px"></i> Поиск...</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <strong>Введите № водительского удостоверения</strong>
                    <div class="input-group mb-3">
                        <input id="driverLicense" type="text" class="form-control rounded-0" value="9909151431">
                        <span class="input-group-append">
                    <button type="button" class="btn btn-success btn-flat">Поиск</button>
                  </span>
                    </div>

                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col (left) -->
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>

<?php
$this->registerJsFile('@web/js/searchDriver.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>​