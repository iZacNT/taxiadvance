<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\CarsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Автомобили';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cars-index">

    <p>
        <?= Html::a('Добавить автомобиль', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin([
            'id' => 'carsData'
    ]); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'fullNameMark',
                'format' => 'raw',
                'value' => function($data){
                    return Html::a($data->getFullNameMark(), ['view', 'id' => $data->id],['style' => 'font-size:20px; font-weight:bold;'])."<br>".Html::a("Редактировать", ['update', 'id' => $data->id],['class' => 'text-green']);
                }
            ],
            //'vin',
            [
                'attribute' => 'mileage',
                'format' => 'raw',
                'value' => function($data){
                    return Html::button('<i class="fas fa-pen-fancy editMileage" data-id="'.$data->id.'" data-field="mileage"></i>', ['class' => 'btn btn-link', 'style' => 'float: right;', 'data-toggle'=>"tooltip" ,'data-placement'=>"right", 'title'=> "Редактировать пробег"])."<br><br><strong>".Yii::$app->formatter->asDecimal($data->mileage).'км.</strong>'  ;
                }
            ],
            [
                'attribute' => 'inspection_to',
                'contentOptions'   =>   function ($model) {
                    return $model->generateContentOptionCalcData($model->inspection_to, $model->inspection);
                },
                'format' => 'raw',
                'value' => function($data){
                    return Html::button('<i class="fas fa-pen-fancy editMileage" data-id="'.$data->id.'" data-field="inspection"></i>',
                            ['class' => 'btn btn-link', 'style' => 'float: right;', 'data-toggle'=>"tooltip" ,'data-placement'=>"right", 'title'=> "Установить пробег Тех. осмотра"])
                            .Html::button('<i class="fas fa-check-double editCurrentMileage" data-id="'.$data->id.'" data-field="inspection" data-mileage="'.$data->mileage.'"></i>',
                            ['class' => 'btn btn-link', 'style' => 'float: right;', 'data-toggle'=>"tooltip" ,'data-placement'=>"right", 'title'=> "Установить текущий пробег"])
                            .'<br><br>'
                        .Yii::$app->formatter->asDecimal($data->inspection_to).'км.';
                }
            ],
            [
                'attribute' => 'inspection_grm_to',
                'contentOptions'   =>   function ($model) {
                    return $model->generateContentOptionCalcData($model->inspection_grm_to, $model->inspection_grm);
                },
                'format' => 'raw',
                'value' => function($data){
                    return Html::button('<i class="fas fa-pen-fancy editMileage" data-id="'.$data->id.'" data-field="inspection_grm"></i>',
                            ['class' => 'btn btn-link', 'style' => 'float: right;', 'data-toggle'=>"tooltip" ,'data-placement'=>"right", 'title'=> "Установить пробег ГРМ"])
                        .Html::button('<i class="fas fa-check-double editCurrentMileage" data-id="'.$data->id.'" data-field="inspection_grm" data-mileage="'.$data->mileage.'"></i>',
                            ['class' => 'btn btn-link', 'style' => 'float: right;', 'data-toggle'=>"tooltip" ,'data-placement'=>"right", 'title'=> "Установить текущий пробег"])
                        .'<br><br>'
                        .Yii::$app->formatter->asDecimal($data->inspection_grm_to).'км.';
                }
            ],
            [
                'attribute' => 'inspection_gas_to',
                'contentOptions'   =>   function ($model) {
                    return $model->generateContentOptionCalcData($model->inspection_gas_to, $model->inspection_gas);
                },

                'format' => 'raw',
                'value' => function($data){
                    return Html::button('<i class="fas fa-pen-fancy editMileage" data-id="'.$data->id.'" data-field="inspection_gas"></i>',
                            ['class' => 'btn btn-link', 'style' => 'float: right;', 'data-toggle'=>"tooltip" ,'data-placement'=>"right", 'title'=> "Установить пробег Газового оборудования"])
                        .Html::button('<i class="fas fa-check-double editCurrentMileage" data-id="'.$data->id.'" data-field="inspection_gas" data-mileage="'.$data->mileage.'"></i>',
                            ['class' => 'btn btn-link', 'style' => 'float: right;', 'data-toggle'=>"tooltip" ,'data-placement'=>"right", 'title'=> "Установить текущий пробег"])
                        .'<br><br>'
                        .Yii::$app->formatter->asDecimal($data->inspection_gas_to).'км.';
                }
            ],
            [
                'attribute' => 'inspection_gearbox_to',
                'contentOptions'   =>   function ($model) {
                    return $model->generateContentOptionCalcData($model->inspection_gearbox_to, $model->inspection_gearbox);
                },
                'format' => 'raw',
                'value' => function($data){
                    return Html::button('<i class="fas fa-pen-fancy editMileage" data-id="'.$data->id.'" data-field="inspection_gearbox"></i>',
                            ['class' => 'btn btn-link', 'style' => 'float: right;', 'data-toggle'=>"tooltip" ,'data-placement'=>"right", 'title'=> "Установить пробег Коробки передач"])
                        .Html::button('<i class="fas fa-check-double editCurrentMileage" data-id="'.$data->id.'" data-field="inspection_gearbox" data-mileage="'.$data->mileage.'"></i>',
                            ['class' => 'btn btn-link', 'style' => 'float: right;', 'style' => 'float: right;', 'data-toggle'=>"tooltip" ,'data-placement'=>"right", 'title'=> "Установить текущий пробег"])
                        .'<br><br>'
                        .Yii::$app->formatter->asDecimal($data->inspection_gearbox_to).'км.';
                }
            ],
            [
                'attribute' => 'inspection_camber_to',
                'contentOptions'   =>   function ($model) {
                        return $model->generateContentOptionCalcData($model->inspection_camber_to, $model->inspection_camber_to);
                    },
                'format' => 'raw',
                'value' => function($data){
                    return Html::button('<i class="fas fa-pen-fancy editMileage" data-id="'.$data->id.'" data-field="inspection_camber"></i>',
                            ['class' => 'btn btn-link', 'style' => 'float: right;', 'data-toggle'=>"tooltip" ,'data-placement'=>"right", 'title'=> "Установить пробег Развала"])
                        .Html::button('<i class="fas fa-check-double editCurrentMileage" data-id="'.$data->id.'" data-field="inspection_camber" data-mileage="'.$data->mileage.'"></i>',
                            ['class' => 'btn btn-link', 'style' => 'float: right;', 'style' => 'float: right;', 'data-toggle'=>"tooltip" ,'data-placement'=>"right", 'title'=> "Установить текущий пробег"])
                        .'<br><br>'
                        .Yii::$app->formatter->asDecimal($data->inspection_camber_to).'км.';
                }
            ],

            //'status',
            //'comment',
            //'name_insurance',
            //'date_osago',
            //'date_dosago',
            //'date_kasko',
//            [
//                'attribute' => 'name_owner',
//                'filter' => $searchModel->getAllOwner(),
//                'format' => 'raw',
//                'value' => 'name_owner'
//            ],
//            //'fuel',
//            [
//                'attribute' => 'filial',
//                'filter' => (new \backend\models\Filials())->getAllFilials(),
//                'format' => 'raw',
//                'value' => 'filialData.name'
//            ],

        ],
        'pager' => [
            'maxButtonCount' => 5,
            'options' => ['class' => 'pagination pagination-sm'],
            'linkContainerOptions' => ['class' => 'page-item'],
            'linkOptions' => ['class' => 'page-link']
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>

<?php

$css= <<< CSS

table thead {
    position: sticky; 
    top: 0;
    background: #ffffff;  
}

CSS;

$this->registerCss($css, ["type" => "text/css"], "myStyles" );
$this->registerJsFile('@web/js/cars/editCarmileage.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>​

