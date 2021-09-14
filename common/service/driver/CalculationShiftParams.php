<?php

namespace common\service\driver;

use app\models\Calculation;
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
        return (($this->plan > $this->getFullAmountSummDriver()) ? Constants::BIGGER_PLAN : Constants::LESS_PLAN);
    }

    /**
     * Заработанная сумма
     */
    public function getFullAmountSummDriver()
    {
        $this->fullAmountSumm = $this->inputAmount+$this->bonusYandex;
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

        return [
            'billing' => round($billingAdditionally - $this->balanceYandex),
            'additionaly' => round($this->getAdditionally()),
            'summPark' => round($summPark),
            'summDriver' => round($summDriver),
            'percentPark' => $this->percentPark,
            'percentDriver' => $this->percentDriver,
            'plan' => $this->plan
        ];
    }
}