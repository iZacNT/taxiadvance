<?php

namespace common\service\driver;

use backend\models\CashRegister;
use backend\models\Deposit;
use backend\models\Driver;
use backend\models\DriverBilling;
use backend\models\DriverTabel;
use common\service\constants\Constants;
use Yii;

class BillingDeleteService
{

    /**
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function deleteBilling(int $id)
    {
        $billing = DriverBilling::find()->where(['id' => $id])->one();
        $deposit = Deposit::find()->where(['id' => $billing->deposit_id])->one();
        $cashRegistry = CashRegister::find()->where(['id'=>$billing->cash_registry_id])->one();
        $cashRegistryDebt = CashRegister::find()->where(['id'=>$billing->cash_registry_debt_id])->one();
        $tabel= DriverTabel::find()->where(['id' => $billing->shift_id])->one();

        $shiftsDriver = $this->getAllShiftsInArray($billing->driver_id);
        $key = array_search($billing->shift_id, array_column($shiftsDriver, 'id_shift'));

        $this->changeWorkDateDriver($billing->driver_id, $shiftsDriver, $key);
        $this->changeDataInTabel($tabel, $shiftsDriver[$key]);

        if (!empty($deposit)) {
            $deposit->delete();
            Yii::debug("Удалили данные Депозита",__METHOD__);
        }
        Yii::debug("Удалили данные из Депозита",__METHOD__);


        if (!empty($cashRegistry)) {
            $cashRegistry->delete();
            Yii::debug("Удалили данные Депозита",__METHOD__);
        }

        Yii::debug("Удалили данные из Кассы",__METHOD__);
        if (!empty($cashRegistryDebt)) {
            $cashRegistryDebt->delete();
            Yii::debug("Удалили данные долга из Кассы",__METHOD__);
        }
        $billing->delete();
        Yii::debug("Удалили данные Биллинг",__METHOD__);

    }

    public function getAllShiftsInArray($driver_id): array
    {
        $driverShift = [];
        $shifts = DriverTabel::find()
            ->where(['driver_id_day' => $driver_id])
            ->orWhere(['driver_id_night' => $driver_id])
            ->orderBy(['work_date' => SORT_DESC])
            ->all();
        foreach($shifts as $shift){
            if ($shift->driver_id_day ==  $driver_id){
                $driverShift =$this->addRowInArrayDay( $shift, $driverShift);
            }
            if ($shift->driver_id_night ==  $driver_id){
                $driverShift = $this->addRowInArrayNight( $shift, $driverShift);
            }
        }

        return $driverShift;
    }

    private function addRowInArrayDay(DriverTabel $shift, array $driverShift): array
    {
        $fullTimeShift = $shift->work_date + (9*60*60);

        $driverShift[] = [
            'id_shift' => $shift->id,
            'car_id' => $shift->car_id,
            'car_full_name' => $shift->carInfo->fullNameMark,
            'car_mark' => $shift->carInfo->mark,
            'car_fuel' => $shift->carInfo->fuel,
            'car_fuel_label' => Constants::getFuel()[$shift->carInfo->fuel],
            'work_date' => $fullTimeShift,
            'period' => Constants::PERIOD_DAY,
            'card' => $shift->card_day,
            'sum_card' => $shift->sum_card_day,
            'phone' => $shift->phone_day,
            'sum_phone' => $shift->sum_phone_day,
            'status_shift' => DriverTabel::labelStatusShift()[$shift->status_day_shift],
            'date_close_shift' => $shift->date_close_day_shift,
            'comment' => $shift->comment_day,
        ];
        return $driverShift;
    }

    private function addRowInArrayNight(DriverTabel $shift, array $driverShift): array
    {
        $fullTimeShift = $shift->work_date + (21*60*60);

        $driverShift[] = [
            'id_shift' => $shift->id,
            'car_id' => $shift->car_id,
            'car_full_name' => $shift->carInfo->fullNameMark,
            'car_mark' => $shift->carInfo->mark,
            'car_fuel' => $shift->carInfo->fuel,
            'car_fuel_label' => Constants::getFuel()[$shift->carInfo->fuel],
            'work_date' => $fullTimeShift,
            'period' => Constants::PERIOD_NIGHT,
            'card' => $shift->card_night,
            'sum_card' => $shift->sum_card_night,
            'phone' => $shift->phone_night,
            'sum_phone' => $shift->sum_phone_night,
            'status_shift' => DriverTabel::labelStatusShift()[$shift->status_night_shift],
            'date_close_shift' => $shift->date_close_night_shift,
            'comment' => $shift->comment_night,
        ];

        return $driverShift;
    }

    private function changeWorkDateDriver($driver_id, array $shiftsDriver, int $key)
    {

        if (!empty($shiftsDriver)){
            $nextShift = (array_key_exists($key+1,$shiftsDriver)) ? $shiftsDriver[$key+1] : ['date_close_shift' => time()-(24*60*60)];
        }else{
            $nextShift['date_close_shift'] = time()-(24*60*60);
        }
        $driver = Driver::find()->where(['id' => $driver_id])->one();
        $driver->stringShiftClosing = "String";
        $driver->shift_closing = $nextShift['date_close_shift'];
        $driver->save();
        Yii::debug($driver->errors,__METHOD__);

        Yii::debug("Изменили дату на:".Yii::$app->formatter->asDatetime($driver->shift_closing),__METHOD__);

    }

    private function changeDataInTabel(DriverTabel $tabel, $shiftsDriver)
    {
        if($shiftsDriver['period'] == Constants::PERIOD_DAY){
            $tabel->status_day_shift = 1;
            $tabel->date_close_day_shift = null;
            $tabel->billing_id_day = null;
        }

        if($shiftsDriver['period'] == Constants::PERIOD_NIGHT){
            $tabel->status_night_shift = 1;
            $tabel->date_close_night_shift = null;
            $tabel->billing_id_night = null;
        }

        $tabel->save();
        Yii::debug("Удалили данные из табеля",__METHOD__);

    }
}