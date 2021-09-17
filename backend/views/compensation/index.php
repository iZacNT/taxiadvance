
<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this \yii\web\View */

$this->title = 'Компенсации';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="compensation-index">
    <p>
        <?= Html::button('Сохранить компенсации', ['class' => 'btn btn-success saveCompensation']) ?>
        <?= Html::button('Изменить компенсации', ['class' => 'btn btn-success updateCompensation']) ?>

    </p>
<div class="col-md-6">
    <table class="table table-hover text-nowrap">
        <thead>
        <tr>
            <th>Сумма</th>
            <th>День</th>
            <th>Ночь</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>10 000</td>
            <td>
                <input type="text" id="day10000" class="form-control">
            </td>
            <td>
                <input type="text" id="night10000" class="form-control">
            </td>
        </tr>
        <tr>
            <td>9 500</td>
            <td>
                <input type="text" id="day9500" class="form-control">
            </td>
            <td>
                <input type="text" id="night9500" class="form-control">
            </td>
        </tr>
        <tr>
            <td>9 000</td>
            <td>
                <input type="text" id="day9000" class="form-control">
            </td>
            <td>
                <input type="text" id="night9000" class="form-control">
            </td>
        </tr>
        <tr>
            <td>8 500</td>
            <td>
                <input type="text" id="day8500" class="form-control">
            </td>
            <td>
                <input type="text" id="night8500" class="form-control">
            </td>
        </tr>
        <tr>
            <td>8 000</td>
            <td>
                <input type="text" id="day8000" class="form-control">
            </td>
            <td>
                <input type="text" id="night8000" class="form-control">
            </td>
        </tr>
        <tr>
            <td>7 500</td>
            <td>
                <input type="text" id="day7500" class="form-control">
            </td>
            <td>
                <input type="text" id="night7500" class="form-control">
            </td>
        </tr>
        <tr>
            <td>7 000</td>
            <td>
                <input type="text" id="day7000" class="form-control">
            </td>
            <td>
                <input type="text" id="night7000" class="form-control">
            </td>
        </tr>
        <tr>
            <td>6 500</td>
            <td>
                <input type="text" id="day6500" class="form-control">
            </td>
            <td>
                <input type="text" id="night6500" class="form-control">
            </td>
        </tr>
        <tr>
            <td>6 000</td>
            <td>
                <input type="text" id="day6000" class="form-control">
            </td>
            <td>
                <input type="text" id="night6000" class="form-control">
            </td>
        </tr>
        <tr>
            <td>5 500</td>
            <td>
                <input type="text" id="day5500" class="form-control">
            </td>
            <td>
                <input type="text" id="night5500" class="form-control">
            </td>
        </tr>
        <tr>
            <td>5 000</td>
            <td>
                <input type="text" id="day5000" class="form-control">
            </td>
            <td>
                <input type="text" id="night5000" class="form-control">
            </td>
        </tr>
        <tr>
            <td>4 500</td>
            <td>
                <input type="text" id="day4500" class="form-control">
            </td>
            <td>
                <input type="text" id="night4500" class="form-control">
            </td>
        </tr>
        <tr>
            <td>4 000</td>
            <td>
                <input type="text" id="day4000" class="form-control">
            </td>
            <td>
                <input type="text" id="night4000" class="form-control">
            </td>
        </tr>
        <tr>
            <td>3 500</td>
            <td>
                <input type="text" id="day3500" class="form-control">
            </td>
            <td>
                <input type="text" id="night3500" class="form-control">
            </td>
        </tr>
        <tr>
            <td>3 000</td>
            <td>
                <input type="text" id="day3000" class="form-control">
            </td>
            <td>
                <input type="text" id="night3000" class="form-control">
            </td>
        </tr>
        <tr>
            <td>2 500</td>
            <td>
                <input type="text" id="day2500" class="form-control">
            </td>
            <td>
                <input type="text" id="night2500" class="form-control">
            </td>
        </tr>
        </tbody>
    </table>

    <p>
        <?= Html::button('Сохранить компенсации', ['class' => 'btn btn-success saveCompensation']) ?>
        <?= Html::button('Изменить компенсации', ['class' => 'btn btn-success updateCompensation']) ?>
    </p>
</div>
</div>

<?php
$this->registerJsFile('@web/js/compensation/compensationService.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>