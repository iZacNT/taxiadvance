<?php

namespace common\service\car_repare;


use backend\models\CarRepairs;
use backend\models\DriverTabel;
use common\service\constants\Constants;
use Yii;
use yii\data\ActiveDataProvider;

class CarRepareService
{
    private $car_id;

    public function __construct($id)
    {
        $this->car_id = $id;
    }

    public function openRepare():void
    {
        $currentShift = \Yii::$app->formatter->asBeginDay(time());

        $repare = new CarRepairs();
        $repare->car_id = $this->car_id;
        $repare->date_open_repair = $currentShift;
        $repare->status = CarRepairs::STATUS_OPEN_REPAIR;
        $repare->save();

        $shiftInTabel = $this->getOpenShiftFromTabel($currentShift, $this->car_id);
        if ($shiftInTabel){
            $currentPeriod = Yii::$app->formatter->currentPeriod();
            Yii::debug(Constants::getPeriod()[$currentPeriod], __METHOD__);
            if (Constants::PERIOD_DAY == $currentPeriod){
                $this->deleteShiftsFromPeriod($shiftInTabel,Constants::PERIOD_NIGHT);
                //$this->deleteAllShiftFromNextDay($currentShift,$this->car_id);
            }
        }
        $this->deleteAllShiftFromNextDay($currentShift,$this->car_id);
    }

    public function closeRepare():void
    {
        $currentShift = \Yii::$app->formatter->asBeginDay(time());;
        $repare = CarRepairs::find()->where(['car_id' => $this->car_id])->andWhere(['status' => CarRepairs::STATUS_OPEN_REPAIR])->one();
        $repare->date_close_repare = $currentShift;
        $repare->status = CarRepairs::STATUS_CLOSE_REPAIR;
        $repare->save();

    }


    /**
     * Return id CarsRepair
     * @return int|mixed|null
     */
    public function activeRepair()
    {
        $repair = CarRepairs::find()->where(['car_id' => $this->car_id])->andWhere(['status' => CarRepairs::STATUS_OPEN_REPAIR])->one();
        return (bool)$repair ? $repair->id : 0;
    }


    /**
     * Удаляем все смены начиная с завтрашнего дня.
     * @param int $dateShift
     * return Void
     */
    public function deleteAllShiftFromNextDay(int $dateShift, $car_id):void
    {
        $shifts = DriverTabel::find()->where(['>', 'work_date', $dateShift])->andWhere(['car_id' => $car_id])->all();

        if (!empty($shifts)){
            foreach($shifts as $shift){
                $shift->delete();
            }
        }
    }

    /**
     * Проверям какому временному периоду соответсвует время выхода автомобиля на ремонт и снимаем водителей с смены
     * @param int $nextShift
     * return void
     */
    public function deleteShiftsFromPeriod( DriverTabel $currentShift, $period):void
    {
        if($period == Constants::PERIOD_DAY){
            $currentShift->driver_id_day = null;
            $currentShift->card_day = null;
            $currentShift->sum_card_day = null;
            $currentShift->phone_day = null;
            $currentShift->sum_phone_day = null;
            $currentShift->status_day_shift = DriverTabel::STATUS_SHIFT_OPEN;
            $currentShift->date_close_day_shift = null;
            $currentShift->save();
        }

        if($period == Constants::PERIOD_NIGHT){
            $currentShift->driver_id_night = null;
            $currentShift->card_night = null;
            $currentShift->sum_card_night = null;
            $currentShift->phone_night = null;
            $currentShift->sum_phone_night = null;
            $currentShift->status_night_shift = DriverTabel::STATUS_SHIFT_OPEN;
            $currentShift->date_close_night_shift = null;
            $currentShift->save();
        }
    }


    public function getAllRepairsForDataProvider(): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => CarRepairs::find()->where(['car_id' => $this->car_id])
        ]);
    }

    /**
     * Возвращаем в ремонте ли автомобиль или нет
     * @return bool
     */
    public function hasActiveRepair(): bool
    {
        $activeRepair = CarRepairs::find()->where(['car_id' => $this->car_id])->andWhere(['status' => CarRepairs::STATUS_OPEN_REPAIR])->one();
        return (bool)$activeRepair;
    }

    private function getOpenShiftFromTabel($currentShift, $car_id)
    {
        return DriverTabel::find()->where(['work_date' => $currentShift])->andWhere(['car_id' => $car_id])->one();
    }

}