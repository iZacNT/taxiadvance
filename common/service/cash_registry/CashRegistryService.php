<?php

namespace common\service\cash_registry;

use backend\models\CashRegister;

class CashRegistryService
{
    public function getStatisticCashRegistry(): string
    {
        $cashRegistry = new CashRegister();
        $inCashRegistry = $cashRegistry->getSummInCashRegisterWithDolg();
        $lastClosedData = $cashRegistry->getLastCloseCashRegistryData();
        \Yii::debug("В Кассе");
        \Yii::debug($inCashRegistry);
        \Yii::debug($lastClosedData);
        return $this->prepareHtmlData($inCashRegistry, $lastClosedData);
    }

    public function prepareHtmlData($inCashRegistry, array $lastClosedData): string
    {
        return '
            <div class="col-lg-4 col-6">
            <!-- small card -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>В Кассе: '.\Yii::$app->formatter->asCurrency($inCashRegistry).'</h3>
              </div>
        
              <span href="#" class="small-box-footer">
                <table class="table table-sm table-responsive-sm">
                    '.$this->prepareRowsLastClosedData($lastClosedData).'
                </table>
              </span>
            </div>
          </div>
        ';
    }

    private function prepareRowsLastClosedData(array $lastClosedData): string
    {
        $html = '';
        foreach($lastClosedData as $data){
            $html.= '<tr>
                        <td>'.\Yii::$app->formatter->asDatetime($data['date_time']).'</td>
                        <td><strong>'.\Yii::$app->formatter->asCurrency($data['cash']).'</strong> '.$data['comment'].'</td>
                    </tr>';
        }

        return $html;
    }
}