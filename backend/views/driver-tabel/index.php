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

    <?php Pjax::begin(); ?>
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
    ]); ?>

    <?php Pjax::end(); ?>

</div>
