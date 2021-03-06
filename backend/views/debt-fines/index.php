<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\DebtFinesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Штрафы ДПС';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="debt-fines-index">

    <p>
        <?= Html::a('Добавить Штраф ДПС', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            ['attribute' => 'driverFullName',
                'format' => 'raw',
                'value' => function($data){
                    return Html::a($data->driverInfo->fullName,['view', 'id' => $data->id],['style' => 'font-size: 18px; font-weight:bold;'])."<br />".Html::a('Редактировать',['update', 'id' => $data->id],['class' => 'text-green']);
                }
                ],
            'carFullName',
           // 'date_reason:datetime',
            [
                'attribute' => 'regulation',
                'format' => 'raw',
                'value' => function($data){
                    return (!empty($data->regulation)) ? $data->regulation."<br>
                                               <span style='font-size: 13px; font-weight: lighter'>".Yii::$app->formatter->asDateTime($data->date_reason-(5*60*60))."</span>" : "-";
                }
            ],
            'dette:currency',
            'back:currency',
            'comment:ntext',
            //'car_id',
            //'date_reason',
            //'geo_dtp',
            //'date_dtp',
            //'payable',
            //'date_pay',

            ['class' => 'yii\grid\ActionColumn',
                'template' => Yii::$app->user->identity->templateEditRuls()],
        ],
        'pager' => [
            'maxButtonCount' => 10,
            'options' => ['class' => 'pagination pagination-sm'],
            'linkContainerOptions' => ['class' => 'page-item'],
            'linkOptions' => ['class' => 'page-link']
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
