<?php

use yii\bootstrap4\Dropdown;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this \yii\web\View */
/* @var $dataProvider \yii\data\ActiveDataProvider */

$this->title = 'Планы на день';
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="cars-index">
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title"> Выберите филиал:</h3>
                </div>
                <div class="card-body">
                    <?= Html::dropDownList('s_id', null,
                        $filials, [
                            'class' => 'form-control',
                            'prompt' => 'Все филиалы'
                        ]) ?>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>



