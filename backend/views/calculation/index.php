<?php

use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $filials  */

$this->title = 'Тарифы';
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="calculations-index row">
       <div class="col-md-4">
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
           <div class="btn-group-vertical w-100">
               <?php
                    echo $prepareMarks;
               ?>
           </div>
       </div>
        <div class="col-md-8">
            <table class="table table-hover text-nowrap">
                <thead>
                <tr class="bg-primary">
                    <th></th>
                    <th colspan="2">День < Плана</th>
                    <th colspan="2">Ночь < Плана</th>
                </tr>
                <tr>
                    <th></th>
                    <th>Газ</th>
                    <th class="bg-gray disabled">Бензин</th>
                    <th>Газ</th>
                    <th class="bg-gray disabled">Бензин</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th>% Парка</th>
                    <td>
                        <input type="text" id="gasLessPlanDayPPark" class="form-control dataItemCalculate">
                    </td>
                    <td class="bg-gray disabled">
                        <input type="text" id="gasolineLessPlanDayPPark"  class="form-control dataItemCalculate">
                    </td>
                    <td><input type="text" id="gasLessPlanNightPPark"  class="form-control dataItemCalculate"></td>
                    <td class="bg-gray disabled"><input type="text" id="gasolineLessPlanNightPPark"  class="form-control dataItemCalculate"></td>
                </tr>
                <tr>
                    <th>% Водителя</th>
                    <td><input type="text" id="gasLessPlanDayPDriver" class="form-control dataItemCalculate"></td>
                    <td class="bg-gray disabled"><input type="text" id="gasolineLessPlanDayPDriver"  class="form-control dataItemCalculate"></td>
                    <td><input type="text" id="gasLessPlanNightPDriver"  class="form-control dataItemCalculate"></td>
                    <td class="bg-gray disabled"><input type="text" id="gasolineLessPlanNightPDriver"  class="form-control dataItemCalculate"></td>
                </tr>
                </tbody>
            </table>

            <table class="table table-hover text-nowrap">
                <thead>
                <tr class="bg-primary">
                    <th></th>
                    <th colspan="2">День >= Плана</th>
                    <th colspan="2">Ночь >= Плана</th>
                </tr>
                <tr>
                    <th></th>
                    <th>Газ</th>
                    <th class="bg-gray disabled">Бензин</th>
                    <th>Газ</th>
                    <th class="bg-gray disabled">Бензин</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th>% Парка</th>
                    <td>
                        <input type="text" id="gasBiggerPlanDayPPark" class="form-control dataItemCalculate">
                    </td>
                    <td class="bg-gray disabled">
                        <input type="text" id="gasolineBiggerPlanDayPPark"  class="form-control dataItemCalculate">
                    </td>
                    <td><input type="text" id="gasBiggerPlanNightPPark"  class="form-control dataItemCalculate"></td>
                    <td class="bg-gray disabled"><input type="text" id="gasolineBiggerPlanNightPPark"  class="form-control dataItemCalculate"></td>
                </tr>
                <tr>
                    <th>% Водителя</th>
                    <td><input type="text" id="gasBiggerPlanDayPDriver" class="form-control dataItemCalculate"></td>
                    <td class="bg-gray disabled"><input type="text" id="gasolineBiggerPlanDayPDriver"  class="form-control dataItemCalculate"></td>
                    <td><input type="text" id="gasBiggerPlanNightPDriver"  class="form-control dataItemCalculate"></td>
                    <td class="bg-gray disabled"><input type="text" id="gasolineBiggerPlanNightPDriver"  class="form-control dataItemCalculate"></td>
                </tr>
                </tbody>
            </table>

            <?php echo Html::button("Сохранить", ['class' => 'btn btn-success', 'id' => 'createTarifs', 'style' => 'display: none'])?>
            <?php echo Html::button("Изменить", ['class' => 'btn btn-success', 'id' => 'updateTarifs', 'style' => 'display: none'])?>
        </div>
    </div>

<?php
$this->registerJsFile('@web/js/calculations/calculationsService.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>​

