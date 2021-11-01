<?php
namespace common\service\car_sharing;

use app\models\CarSharing;
use app\models\DriverTabel;
use backend\models\Cars;
use common\service\constants\Constants;
use Yii;
use yii\data\ActiveDataProvider;

class CarSharingService
{
    private $car_id;

    public function __construct($id)
    {
        $this->car_id = $id;
    }

    public function getAllSharingForDataProvider()
    {
        return new ActiveDataProvider([
            'query' => CarSharing::find()->where(['car_id' => $this->car_id])
        ]);
    }

    public function setCarStatusSharing():void
    {
        $car = Cars::find()->where(['id' => $this->car_id])->one();
        $car->status = $car::STATUS_SHARING;
        $car->save();
    }

    public function createSharing(CarSharing $model)
    {
        $currentShift = \Yii::$app->formatter->asBeginDay(time());

        $model->date_start = strtotime($model->stringDateStart);
        $model->date_stop = (!empty($model->stringDateStop)) ? strtotime($model->stringDateStop): null;
        $model->save();

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

        \Yii::debug($model->errors,__METHOD__);
    }

    public function isValidDate($date): bool
    {
        if(!empty($date)){
            $sharings = CarSharing::find()->where(['car_id' => $this->car_id])->all();
            foreach($sharings as $sharing){
                if ($sharing->date_start <= $date && $date <= $sharing->date_stop){
                    return false;
                }
            }
        }
        return true;
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

    private function getOpenShiftFromTabel($currentShift, $car_id)
    {
        return DriverTabel::find()->where(['work_date' => $currentShift])->andWhere(['car_id' => $car_id])->one();
    }

}