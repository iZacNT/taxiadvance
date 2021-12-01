<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model backend\models\CarRepairs */

$this->title = $model->id.": ".$model->carFullName;
$this->params['breadcrumbs'][] = ['label' => 'Ремонты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="car-repairs-view">

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="col-12  ">
        <div class="card card-primary card-tabs">
            <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">Информация</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">Детали</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-one-tabContent">
                    <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                        <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                'id',
                                [
                                    'attribute' => 'car_id',
                                    'format' => 'raw',
                                    'value' => function($data){
                                        return Html::a($data->carFullName, ['cars/view', 'id' => $data->car_id]);
                                    }
                                ],
                                'date_open_repair:datetime',
                                'date_close_repare:datetime',
                                'statusType'
                            ],
                        ]) ?>
                    </div>
                    <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                        <?php Pjax::begin([
                            'id' => 'carRepairsPjax'
                        ]); ?>
                        <p>
                            <?= Html::button('Добавить деталь', ['class' => 'btn btn-primary', 'id' => 'addParts']) ?>
                        </p>

                        <?= GridView::widget([
                            'dataProvider' => $dataProviderStock,
                            'columns' => [
                                [
                                    'attribute' => 'partName',
                                    'format' => 'raw',
                                    'value' => function($data){
                                        return Html::a($data->partName,['stock/view', 'id' => $data->id],['style' => 'font-size:20px; font-weight: bold'])
                                            ."<br>"
                                            .Html::a('Редактировать',['stock/update-from-repair', 'id' => $data->id, 'repair_id' => $data->repair_id],['class' => 'text-green']);
                                    }
                                ],
                                'count',
//                                'repair_id',
                                'date:datetime',
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => '{delete}'
                                ],
                            ],
                        ]); ?>

                        <?php Pjax::end(); ?>
                    </div>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>



</div>

<?php
$this->registerJsFile('@web/js/car_repair/appParts.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>​
