<?php

use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $dataProvider \yii\data\ActiveDataProvider */

$this->title = 'Планы на день';
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="day-plans-index">
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title"> Выберите филиал:</h3>
                    <h3 id="loader" class="card-title float-right" style="display: none;"><i class="fas fa-2x fa-sync-alt fa-spin" style="font-size: 18px"></i> Поиск...</h3>
                </div>
                <div class="card-body">
                    <?= Html::dropDownList('s_id', null,
                        $filials, [
                            'class' => 'form-control selectFilials',
                            'id' => 'selectFilials' ,
                            'prompt' => 'Все филиалы'
                        ]) ?>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- /.form -->
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th style="width: 20px">#</th>
                    <th style="max-width: 180px;">Период</th>
                    <th style="max-width: 80px;" >12 часо</th>
                    <th style="max-width: 80px;" >16 часо</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>1.</td>
                    <td>Выходной/День</td>
                    <td>
                        <input type="text" id="weekenDay12"  class="dataElements"  style="max-width: 80px;" disabled="true">
                    </td>
                    <td><input type="text" id="weekenDay16"  class="dataElements"  style="max-width: 80px;" disabled="true"></td>
                </tr>
                <tr>
                    <td>2.</td>
                    <td>Выходной/Ночь</td>
                    <td>
                        <input type="text" id="weekenNight12" class="dataElements"  style="max-width: 80px;" disabled="true">
                    </td>
                    <td><input type="text" id="weekenNight16" class="dataElements"  style="max-width: 80px;" disabled="true"></td>
                </tr>
                <tr>
                    <td>3.</td>
                    <td>Будний/День</td>
                    <td>
                        <input type="text" id="workingDay12" class="dataElements"  style="max-width: 80px;" disabled="true">
                    </td>
                    <td><input type="text" id="workingDay16" class="dataElements"  style="max-width: 80px;" disabled="true"></td>
                </tr>
                <tr>
                    <td>4.</td>
                    <td>Будний/Ночь</td>
                    <td>
                        <input type="text" id="workingNight12" class="dataElements" style="max-width: 80px;" disabled="true">
                    </td>
                    <td><input type="text" id="workingNight16" class="dataElements"  style="max-width: 80px;" disabled="true"></td>
                </tr>
                <tr>
                    <td colspan="4">
                        <?php echo Html::button("Сохранить", ['class' => 'btn btn-success float-right', 'id' => 'disabledCreateBtn'])?>
                        <?php echo Html::button("Изменить", ['class' => 'btn btn-success float-right', 'id' => 'disabledUpdateBtn', 'style' => 'display: none'])?>
                    </td>
                </tr>
                </tbody>
            </table>
            <!-- /.form -->

        </div>
    </div>

<?php
$this->registerJsFile('@web/js/filials/filialsService.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>​


