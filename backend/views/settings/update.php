
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\Settings */

$this->title = 'Общие настройки: ';
$this->params['breadcrumbs'][] = ['label' => 'Общие настройки', 'url' => ['update']];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="settings-update">

    <div class="col-12 col-sm-12">
        <div class="card card-primary card-tabs">
            <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-tabs-one-information-tab" data-toggle="pill" href="#custom-tabs-one-information" role="tab" aria-controls="custom-tabs-one-information" aria-selected="true">Основные настройки</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-one-deposit-tab" data-toggle="pill" href="#custom-tabs-one-deposit" role="tab" aria-controls="custom-tabs-one-deposit" aria-selected="false">Категории транзакций</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-one-tabContent">
                    <div class="tab-pane fade show active" id="custom-tabs-one-information" role="tabpanel" aria-labelledby="custom-tabs-one-information-tab">

                        <?= $this->render('_form', [
                            'model' => $model,
                        ]) ?>

                    </div>
                    <div class="tab-pane fade" id="custom-tabs-one-deposit" role="tabpanel" aria-labelledby="custom-tabs-one-deposit-tab">
                        <?= $this->render('_formTransaction', [
                            'transactions' => $transactions,
                        ]) ?>
                    </div>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>



</div>
