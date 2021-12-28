<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model backend\models\Cars */

$this->title = $model->getFullNameMark();
$this->params['breadcrumbs'][] = ['label' => 'Автомобили', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="cars-view">

    <p>
        <?= Html::a('Создать', ['create'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Все Автомобили', ['index'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a("Сдать в аренду",['car-sharing/create','id' => $model->id], ['class' => 'btn btn-warning'])?>
        <?= Html::button("Отправить в Ремонт", ['class' => 'btn btn-warning', 'id' => 'goToRepair'])?>
    </p>
<?//= $hasRepair;die; ?>
    <? $proof = 1;
    if ($hasRepair){
        $proof = 2; ?>
        <div class="col-md-12">
            <div class="card bg-warning">
                <div class="card-header">
                    <span class="card-title">Закрыть ремонт???
                        <?= Html::button("Закончить ремонт", ['class' => 'btn btn-danger', 'style' => 'margin-left: 20px;', 'id' => 'closeRepair', 'data-id-repare' => $idRepair])?>
                    </span>

                    <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
            </div>
            <!-- /.card -->
        </div>
    <? } ?>

    <div class="card card-primary card-tabs">
        <?php
            if (Yii::$app->session->hasFlash("error")){
                echo '<div class="alert alert-warning alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h5><i class="icon fas fa-exclamation-triangle"></i> Alert!</h5>
                  '.Yii::$app->session->getFlash('error').'
                </div>';
            }
        ?>
        <div class="card-header p-0 pt-1">
            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">Автомобиль</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-one-sharing-tab" data-toggle="pill" href="#custom-tabs-one-sharing" role="tab" aria-controls="custom-tabs-one-sharing" aria-selected="false">Аренда</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">Ремонты</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="custom-tabs-one-tabContent">
                <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">

                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [

                            'mark',
                            'year',
                            'number',
                            [
                                'attribute' => 'fuel',
                                'format' => 'raw',
                                'value' => function($data){
                                    return \common\service\constants\Constants::getFuel()[$data->fuel];
                                }
                            ],
                            [
                                'attribute' => 'filial',
                                'format' => 'raw',
                                'value' => function($data){
                                    return \backend\models\Filials::getAllFilials()[$data->filial];
                                }
                            ],
                            'vin',
                            [
                                'attribute' => 'status',
                                'format' => 'raw',
                                 'value' => function($data){
                                    return $data->getStatusLabel()[$data->status];
                                 }
                            ],
                            'comment',
                            'name_insurance',
                            'date_osago:date',
                            'date_dosago:date',
                            'date_kasko:date',
                            'name_owner'
                        ],
                    ]) ?>

                </div>
                <div class="tab-pane fade" id="custom-tabs-one-sharing" role="tabpanel" aria-labelledby="custom-tabs-one-sharing-tab">

                    <? Pjax::begin([
                        'id' => 'carSharing'
                    ])?>
                    <?= GridView::widget([
                        'dataProvider' => $dataProviderSharing,
//                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'date_start:datetime',
                            'date_stop:datetime',
                            'comments',
                            ['class' => 'yii\grid\ActionColumn',
                                'controller' => 'car-sharing'
                            ]

                        ],
                    ]); ?>

                    <? Pjax::end()?>
                </div>
                <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">

                    <? Pjax::begin([
                            'id' => 'carRepair'
                    ])?>
                    <?= GridView::widget([
                        'dataProvider' => $dataProviderRepairs,
//                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'date_open_repair:datetime',
                            'date_close_repare:datetime',
                            [
                                'attribute' => 'status',
                                'format' => 'raw',
                                'value' => function($data){
                                        return $data->getStatusLabel()[$data->status];
                                }
                            ],

                        ],
                    ]); ?>

                    <? Pjax::end()?>
                </div>
            </div>
        </div>
        <!-- /.card -->
    </div>

</div>

<?php
$js = <<< JS
let car_id = $model->id;
if ($proof === 2){
    $("#goToRepair").hide();
}

JS;

$this->registerJs( $js, $position = yii\web\View::POS_END, $key = null );

$this->registerJsFile('@web/js/car_repair/carRepairService.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>​