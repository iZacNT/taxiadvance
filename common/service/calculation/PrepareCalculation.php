<?php


namespace common\service\calculation;


use yii\helpers\Html;

class PrepareCalculation
{
    public function prepareCarMarksList(array $marks)
    {
        $result = '';
        foreach ($marks as $key => $value){
            $result .= Html::button($key,['class' => 'btn btn-info markButton', 'style' => 'font-size: 20px', 'data-mark' => $value]);
        }

        return $result;
    }
}