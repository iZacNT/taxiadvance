<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DriverTabelSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Табель';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="driver-tabel-index">

    <p>
        <?= Html::a('Добавить смену', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="card card-primary card-tabs">
        <div class="card-header p-0 pt-1">
            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-one-home-tabel" data-toggle="pill" href="#custom-tabs-one-tabel" role="tab" aria-controls="custom-tabs-one-tabel" aria-selected="true">Табель</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="custom-tabs-one-tabContent">
                <div class="tab-pane fade show active" id="custom-tabs-one-tabel" role="tabpanel" aria-labelledby="custom-tabs-one-home-tabel">
                    <?php Pjax::begin([
                            'id' => 'tabel'
                    ]); ?>
                    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,


                        'columns' => array_merge([
                            [
                                'attribute' => 'fullNameMark',
                                'value' => function($data){
                                    return $data->getFullNameMark();
                                }
                            ],
                        ],$columns),
                        'pager' => [
                            'maxButtonCount' => 5,
                            'options' => ['class' => 'pagination pagination-sm'],
                            'linkContainerOptions' => ['class' => 'page-item'],
                            'linkOptions' => ['class' => 'page-link']
                        ],

                    ]); ?>

                    <?php Pjax::end(); ?>
                </div>
            </div>
        </div>
        <!-- /.card -->
    </div>

</div>

<?php

$drv = json_encode($drivers);
$jsRaschet = <<< JS
let drivers = $drv;
JS;

$this->registerJs( $jsRaschet, $position = yii\web\View::POS_END);
$this->registerJsFile('@web/js/driver-tabel/searchDriverDataInTabel.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>