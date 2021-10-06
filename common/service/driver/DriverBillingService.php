<?php

namespace common\service\driver;

use app\models\DriverBilling;
use backend\models\Deposit;
use backend\models\Driver;
use Yii;

class DriverBillingService
{

    private $plan;
    private $inputAmount;
    private $bonusYandex;
    private $carMark;
    private $fuel;
    private $period;
    private $percentPark;
    private $percentDriver;
    private $balanceYandex;
    private $depo;
    private $debtFromShift;
    private $carWash;
    private $carFuelSumm;
    private $carPhoneSumm;
    private $driverId;
    private $typeDay;
    private $hours;
    private $billing;
    private $summPark;
    private $summDriver;
    private $compensation;
    private $car_id;

    public function __construct($requestPost)
    {
        $this->driverId = $requestPost['driverId'];
        $this->balanceYandex = $requestPost['balanceYandex'];
        $this->bonusYandex = $requestPost['bonusYandex'];
        $this->carMark = $requestPost['carMark'];
        $this->fuel = $requestPost['fuel'];
        $this->period = $requestPost['period'];
        $this->typeDay = $requestPost['typeDay'];
        $this->inputAmount = $requestPost['inputAmount'];
        $this->depo = $requestPost['depo'];
        $this->debtFromShift = $requestPost['debtFromShift'];
        $this->carWash = $requestPost['carWash'];
        $this->carFuelSumm = $requestPost['carFuelSumm'];
        $this->carPhoneSumm = $requestPost['carPhoneSumm'];
        $this->hours = $requestPost['hours'];
        $this->billing = $requestPost['billing'];
        $this->percentPark = $requestPost['percentPark'];
        $this->percentDriver = $requestPost['percentDriver'];
        $this->summPark = $requestPost['summPark'];
        $this->summDriver = $requestPost['summDriver'];
        $this->plan = $requestPost['plan'];
        $this->compensation = $requestPost['compensation'];
        $this->car_id = $requestPost['car_id'];
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function saveAmount():bool
    {
        $searchShift = DriverBilling::find()
            ->where(['driver_id' => $this->driverId])
            ->andWhere(['date_billing' => Yii::$app->formatter->asBeginDay(time())])
            ->one();
        if (empty($searchShift)){
            $driverBilling = new DriverBilling();
            $driverBilling->driver_id = $this->driverId;
            $driverBilling->date_billing = Yii::$app->formatter->asBeginDay(time());
            $driverBilling->period = $this->period;
            $driverBilling->balance_yandex = $this->balanceYandex;
            $driverBilling->bonus_yandex = $this->bonusYandex;
            $driverBilling->car_mark = $this->carMark;
            $driverBilling->fuel = $this->fuel;
            $driverBilling->type_day = $this->typeDay;
            $driverBilling->input_amount = $this->inputAmount;
            $driverBilling->depo = $this->depo;
            $driverBilling->debt_from_shift = $this->debtFromShift;
            $driverBilling->car_wash = $this->carWash;
            $driverBilling->car_fuel_summ = $this->carFuelSumm;
            $driverBilling->car_phone_summ = $this->carPhoneSumm;
            $driverBilling->hours = $this->hours;
            $driverBilling->billing = $this->billing;
            $driverBilling->percent_park = $this->percentPark;
            $driverBilling->percent_driver = $this->percentDriver;
            $driverBilling->summ_park = $this->summPark;
            $driverBilling->summ_driver = $this->summDriver;
            $driverBilling->plan = $this->plan;
            $driverBilling->compensations = $this->compensation;
            $driverBilling->car_id = $this->car_id;
            $driverBilling->save();

            $driver = Driver::find()->where(['id' => $this->driverId])->one();
            $driver->shift_closing = time();
            $driver->save(false);

            if(!empty($this->depo)){
                $deposit = new Deposit();
                $deposit->driver_id = $this->driverId;
                $deposit->contributed = $this->depo;
                $deposit->created_at = time();
                $deposit->comment = "Внесение от смены ".Yii::$app->formatter->asDatetime(time());
                $deposit->save();
            }
        }else{
            return false;
        }
        return true;
    }
}