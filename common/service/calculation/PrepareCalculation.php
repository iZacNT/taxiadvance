<?php


namespace common\service\calculation;


use yii\helpers\Html;

class PrepareCalculation
{
    public function prepareCarMarksList(array $marks)
    {
        $result = '';
        foreach ($marks as $mark){
            $result .= Html::button($mark['mark'],['class' => 'btn btn-info markButton', 'style' => 'font-size: 20px', 'data-mark' => $mark['mark']]);
        }

        return $result;
    }
}