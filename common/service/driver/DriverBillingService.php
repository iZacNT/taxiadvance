<?php

namespace common\service\driver;

use backend\models\CashRegister;
use backend\models\DriverBilling;
use backend\models\DriverTabel;
use backend\models\Deposit;
use backend\models\Driver;
use common\service\constants\Constants;
use Exception;
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
    private $rolling;
    private $shift_id;

    private $depositId;
    private $cashRegistryID;
    private $cashRegistryDebtID;

    public function __construct($requestPost)
    {
        $this->driverId = $requestPost['driverId'];
        $this->balanceYandex = $requestPost['balanceYandex'];
        $this->bonusYandex = $requestPost['bonusYandex'];
        $this->carMark = $requestPost['carMark'];
        $this->rolling = $requestPost['rolling'];
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


    public function saveAmount(): array
    {
        $searchShift = DriverBilling::find()
            ->where(['driver_id' => $this->driverId])
            ->andWhere(['shift_id' => $this->shift_id])
            ->one();

        $resultAnswer = [];
        if (empty($searchShift)){
            try {
                $billing = $this->saveDriverBilling();
                Yii::debug("Сохранение Биллинга: Успешно.", __METHOD__);
                $resultAnswer[] = [
                    'result' => 'true',
                    'message' => 'Сохранение Биллинга: Успешно.'
                ];
            }catch (Exception $exception){
                Yii::debug("Сохранение Биллинга: Успешно. ".$exception->getMessage(), __METHOD__);
                $resultAnswer[] = [
                    'result' => 'false',
                    'message' => 'Сохранение Биллинга: Не успешно!'
                ];
            }


            $driver = Driver::find()->where(['id' => $this->driverId])->one();
            $this->saveDriverShiftTime($driver);

            if(!empty($this->depo)){
                try {
                    $this->saveDeposit(
                        $this->driverId,
                        $this->depo,
                        time(),
                        "Внесение от смены ".Yii::$app->formatter->asDatetime(time())
                    );
                    Yii::debug("Сохранение Депозита: ".$this->depo, __METHOD__);
                    $resultAnswer[] = [
                        'result' => 'true',
                        'message' => 'Депозит сохранен: ' . $this->depo . "руб."
                    ];
                }catch (Exception $exception){
                    Yii::debug("Депозит не сохранен! ".$exception->getMessage(), __METHOD__);
                    $resultAnswer[] = [
                        'result' => 'false',
                        'message' => 'Депозит не сохранен сохранен!'
                    ];
                }
            }else{
                $this->depositId = 0;
            }

            $driverShift = DriverTabel::find()->where(['id' => $this->shift_id])->one();
            if ($driverShift){
                try {
                    $this->saveShiftData($driverShift, $billing->id);
                    $resultAnswer[] = [
                        'result' => 'true',
                        'message' => 'Данные в Табеле сохранены!'
                    ];
                }catch (Exception $exception){
                    Yii::debug('Данные в Табеле не сохранены! '.$exception->getMessage(), __METHOD__);
                    $resultAnswer[] = [
                        'result' => 'false',
                        'message' => 'Данные в Табеле не сохранены! ' . $exception->getMessage()
                    ];
                }

            }


            try {
                $this->saveToCashRegister(
                    time(),
                    ($this->isPrihod()) ? CashRegister::TYPE_PRIHOD : CashRegister::TYPE_RASHOD,
                    ($this->isPrihod()) ? $this->billing : $this->billing * (-1),
                    "Расчет водителя: ".$driver->fullName
                );
                $resultAnswer[] = [
                    'result' => 'true',
                    'message' => 'Данные в Кассе сохранены! Приход/Расход.'
                ];
            }catch(Exception $exception){
                Yii::debug('Данные в Кассе не сохранены! '.$exception->getMessage(), __METHOD__);
                $resultAnswer[] = [
                    'result' => 'false',
                    'message' => 'Данные в Кассе не сохранены!'
                ];
            }

            if ($this->debtFromShift > 0 ) {

                try {
                    $this->saveToCashRegister(
                        time()+1,
                        CashRegister::TYPE_DOLG_PO_SMENE,
                        $this->debtFromShift,
                        "Водитель: ".$driver->fullName
                    );
                    $resultAnswer[] = [
                        'result' => 'true',
                        'message' => 'Данные в Кассе сохранены! Долг по смене.'
                    ];
                }catch (Exception $exception){
                    Yii::debug('Данные в Кассе не сохранены! Долг по смене. '.$exception->getMessage(), __METHOD__);
                    $resultAnswer[] = [
                        'result' => 'false',
                        'message' => 'Данные в Кассе не сохранены! Долг по смене.'
                    ];
                }

            }

            $this->saveResultDataID(
                $billing,
                $this->depositId,
                $this->cashRegistryID,
                $this->cashRegistryDebtID
            );

        }else{
            Yii::debug('Расчет уже произведен!', __METHOD__);
            $resultAnswer[] = [
                'result' => 'false',
                'message' => 'Смена уже сохранена, у Вас нет прав для ее изменения!'
            ];
        }

        return $resultAnswer;
    }

    public function saveDriverBilling(): DriverBilling
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
            $driverBilling->rolling = $this->rolling;
            $driverBilling->shift_id = $this->shift_id;
            $driverBilling->save();
        return $driverBilling;
    }

    public function saveToCashRegister(int $date,int $type, int $cash, string $comment):void
    {
        $cashRegistry = new CashRegister();
        $cashRegistry->date_time = $date;
        $cashRegistry->type_cash = $type;
        $cashRegistry->cash = $cash;
        $cashRegistry->comment = $comment;
        $cashRegistry->save();
        $cashRegistry->refresh();

        if ($type == CashRegister::TYPE_DOLG_PO_SMENE){
            $this->cashRegistryDebtID = $cashRegistry->id;
        }else{
            $this->cashRegistryID = $cashRegistry->id;
            $this->cashRegistryDebtID = 0;

        }
        Yii::debug("Сохранение В кассу. Тип: ".CashRegister::getTypeCash()[$type], __METHOD__);

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

    private function saveDeposit(int $driverid,int $depo,int $time,string $comment): void
    {
        $deposit = new Deposit();
        $deposit->driver_id = $driverid;
        $deposit->contributed = $depo;
        $deposit->created_at = $time;
        $deposit->comment = $comment;
        $deposit->save();
        $deposit->refresh();
        $this->depositId = $deposit->id;

    }

    private function saveShiftData(DriverTabel $driverShift,int $idBilling): void
    {
        if($driverShift->driver_id_day == $this->driverId) {
            $driverShift->status_day_shift = $driverShift::STATUS_SHIFT_CLOSE;
            $driverShift->billing_id_day = $idBilling;
            $driverShift->date_close_day_shift = time();

            Yii::debug("Дневная смена", __METHOD__);

        }
        if($driverShift->driver_id_night == $this->driverId) {
            $driverShift->status_night_shift = $driverShift::STATUS_SHIFT_CLOSE;
            $driverShift->billing_id_night = $idBilling;
            $driverShift->date_close_night_shift = time();

            Yii::debug("Ночная смена", __METHOD__);
        }

        $driverShift->save();
        $this->tabelShiftID = $driverShift->id;

    }

    private function saveResultDataID(DriverBilling $billing,int $depositId ,int $cashRegistryID,int $cashRegistryDebtID):void
    {
        $billing->deposit_id = $depositId;
        $billing->cash_registry_id = $cashRegistryID;
        if ($cashRegistryDebtID) $billing->cash_registry_debt_id = $cashRegistryDebtID;

        Yii::debug([
            'Deposit ID' => $billing->deposit_id,
            'Cash Registry ID' => $billing->cash_registry_id,
            'Cash Registry Debt ID' => $billing->cash_registry_debt_id
        ],__METHOD__);
        $billing->save();
    }

}