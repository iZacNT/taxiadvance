<?php

namespace common\service\driver;

use app\models\CashRegister;
use app\models\DriverBilling;
use app\models\DriverTabel;
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
    private $shift_id;

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
        $this->shift_id = $requestPost['shift_id'];
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
            $this->saveDriverBilling();

            $driver = Driver::find()->where(['id' => $this->driverId])->one();
            $this->saveDriverShiftTime($driver);


            if(!empty($this->depo)){
                $this->saveDeposit(
                    $this->driverId,
                    $this->depo,
                    time(),
                    "Внесение от смены ".Yii::$app->formatter->asDatetime(time())
                );
            }

            $driverShift = DriverTabel::find()->where(['id' => $this->shift_id])->one();

            if($driverShift->driver_id_day == $this->driverId) {
                $driverShift->status_day_shift = $driverShift::STATUS_SHIFT_CLOSE;
                $driverShift->date_close_day_shift = time();
                $driverShift->save();
                Yii::debug("day", __METHOD__);

            }
            if($driverShift->driver_id_night == $this->driverId) {
                $driverShift->status_night_shift = $driverShift::STATUS_SHIFT_CLOSE;
                $driverShift->date_close_night_shift = time();
                $driverShift->save();
                Yii::debug("night", __METHOD__);

            }

            $this->saveToCashRegister(
                time(),
                ($this->isPrihod()) ? CashRegister::TYPE_PRIHOD : CashRegister::TYPE_RASHOD,
                ($this->isPrihod()) ? $this->billing : $this->billing * (-1),
                "Расчет водителя: ".$driver->fullName
            );

            if ($this->debtFromShift > 0 ) {
                $this->saveToCashRegister(
                    time()+1,
                    CashRegister::TYPE_DOLG_PO_SMENE,
                    $this->debtFromShift,
                    "Водитель: ".$driver->fullName
                );
            }

        }else{
            return false;
        }
        return true;
    }

    public function saveDriverBilling()
    {
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
        $driverBilling->debt_from_shift = 0;
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
        Yii::debug("Сохранение Биллинга: ".$driverBilling->save(), __METHOD__);
    }

    public function saveToCashRegister(int $date,int $type, int $cash, string $comment):void
    {
        $cashRegistry = new CashRegister();
        $cashRegistry->date_time = $date;
        $cashRegistry->type_cash = $type;
        $cashRegistry->cash = $cash;
        $cashRegistry->comment = $comment;
        Yii::debug("Сохранение В кассу: ".$cashRegistry->save()."; Тип: ".CashRegister::getTypeCash()[$type], __METHOD__);
    }

    public function isPrihod(): bool
    {
        return $this->billing > 0;
    }

    private function saveDriverShiftTime(Driver $driver)
    {
        $driver->shift_closing = time();
        Yii::debug("Сохранение Времени смены: ".$driver->save(false)."; Время: ".$driver->shift_closing, __METHOD__);

    }

    private function saveDeposit(int $driverid,int $depo,int $time,string $comment):void
    {
        $deposit = new Deposit();
        $deposit->driver_id = $driverid;
        $deposit->contributed = $depo;
        $deposit->created_at = $time;
        $deposit->comment = $comment;
        Yii::debug("Сохранение Депозита: ".$deposit->save(), __METHOD__);
    }
}