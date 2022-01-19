<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\DriverSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Водители';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="driver-index">

    <p>
        <?= Html::a('Добавить водителя', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            [
                'attribute' => 'fullName',
                'label' => 'ФИО',
                'format' => 'raw',
                'value' => function($data){
                    return Html::a($data->getFullName(), ['view', 'id' => $data->id],['style' => 'font-size: 18px; font-weight: bold'])."<br>".Html::a('Редактировать', ['update', 'id' => $data->id],['class' => 'text-green']);
                }
            ],
            'phone',
            'driving_license',
            [
                'attribute' => 'status',
                'filter' => (new backend\models\Driver)->getStatusList(),
                'format' => 'raw',
                'value' => function($data){

                        return Yii::$app->formatter->asDriverStatus($data,$data->status);
                },
            ],
            [
                'class' => \common\service\columns\DepositDebtColumn::className(),
                'attribute' => 'deposit',
                'label' => 'Деозит-Долг',
                'format' => 'raw',
                'value' => function($data){

                }
            ],
//            [
//                'attribute' => 'adress',
//                'label' => 'Адрес',
//                'value' => function($data){
//                        return $data->getAdress();
//                }
//            ],
            //'commens:ntext',
            //'passport',
            //'date_of_issue',
            //'who_issued_it',
            //'city',
            //'street',
            //'hous',
            //'corpus',
            //'appartament',

//            ['class' => 'yii\grid\ActionColumn',
//                'template' => '{delete}'
//            ],
        ],
        'pager' => [
            'maxButtonCount' => 5,
            'options' => ['class' => 'pagination pagination-sm'],
            'linkContainerOptions' => ['class' => 'page-item'],
            'linkOptions' => ['class' => 'page-link']
        ],
    ]); ?>


</div>
