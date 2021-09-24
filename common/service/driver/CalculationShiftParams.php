<?php

namespace common\service\driver;

use app\models\Calculation;
use app\models\Compensation;
use common\service\constants\Constants;
use yii\db\ActiveQuery;

class CalculationShiftParams {

    private $plan;
    private $inputAmount;
    private $bonusYandex;
    private $carMark;
    private $fuel;
    private $filial;
    private $period;
    private $percentPark;
    private $percentDriver;
    private $fullAmountSumm;
    private $balanceYandex;
    private $depo;
    private $debtFromShift;
    private $carWash;
    private $carFuelSumm;
    private $carPhoneSumm;

    public function __construct($requestPost, $plan)
    {
        $this->plan = $plan;
        $this->inputAmount = $requestPost['inputAmount'];
        $this->bonusYandex = $requestPost['bonusYandex'];
        $this->carMark = $requestPost['carMark'];
        $this->fuel = $requestPost['fuel'];
        $this->filial = $requestPost['filial'];
        $this->period = $requestPost['period'];
        $this->balanceYandex = $requestPost['balanceYandex'];
        $this->depo = $requestPost['depo'];
        $this->debtFromShift = $requestPost['debtFromShift'];
        $this->carWash = $requestPost['carWash'];
        $this->carFuelSumm = $requestPost['carFuelSumm'];
        $this->carPhoneSumm = $requestPost['carPhoneSumm'];
    }

    /**
     * Получаем тип плана: больше заработанной суммы или нет
     * @return int
     */
    public function getPlanType()
    {
        \Yii::debug("Планы ".$this->plan,__METHOD__);
        \Yii::debug("Планы+бонус ".$this->getFullAmountSummDriver(),__METHOD__);
        return (($this->plan > $this->getFullAmountSummDriver()) ? Constants::LESS_PLAN : Constants::BIGGER_PLAN);


    }

    /**
     * Заработанная сумма
     */
    public function getFullAmountSummDriver()
    {
        $this->fullAmountSumm = $this->inputAmount+$this->bonusYandex;
        return $this->fullAmountSumm;
    }

    /**
     * Проценты парка и водителя
     */
    public function getPercentOfAmount()
    {
        $calculation = Calculation::find()
            ->where(['filial' => $this->filial])
            ->andWhere(['fuel' => $this->fuel])
            ->andWhere(['car_mark' => $this->carMark])
            ->andWhere(['plan' => $this->getPlanType()])
            ->andWhere(['period' => $this->period])
            ->one();
        \Yii::debug("Планы ".serialize($this->getPlanType()),__METHOD__);
        $this->percentPark = $calculation->calculation_park;
        $this->percentDriver = $calculation->calculation_driver;
    }

    /**
     * Сумма дополнительных расходов за смену водителем
     * @return mixed
     */
    public function getAdditionally()
    {
        return $this->depo+$this->debtFromShift+$this->carWash+$this->carFuelSumm+$this->carPhoneSumm;
    }


    /**
     * Полный расчет
     * @return array
     */
    public function getGeneralAmount()
    {
        $this->getPercentOfAmount();
        $summPark = ($this->fullAmountSumm/100)*$this->percentPark;
        $summDriver = ($this->fullAmountSumm/100)*$this->percentDriver;

        $billingAdditionally = $summPark+$this->getAdditionally();
        $compensation = $this->getCompensations();
        return [
            'billing' => round($billingAdditionally - $compensation - $this->balanceYandex),
            'additionaly' => round($this->getAdditionally()),
            'summPark' => round($summPark),
            'summDriver' => round($summDriver),
            'percentPark' => $this->percentPark,
            'percentDriver' => $this->percentDriver,
            'plan' => $this->plan,
            'compensation' => $compensation
        ];
    }

    public function getCompensations(): int
    {
        $compensation = Compensation::find()->where(['<=','summ', $this->inputAmount])->orderBy(['summ' => SORT_DESC])->all();
        if (!empty($compensation) && $this->filial == 2){
            if ($this->period == Constants::PERIOD_NIGHT) {
                return $compensation[0]->night;
            }
            return $compensation[0]->day;
        }

        return 0;
    }
}