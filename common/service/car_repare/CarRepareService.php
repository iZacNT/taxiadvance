<?php

namespace common\service\car_repare;


use app\models\CarRepairs;
use app\models\DriverTabel;
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
        $currentShift = \Yii::$app->formatter->asCurrentShift();
        $repare = new CarRepairs();
        $repare->car_id = $this->car_id;
        $repare->date_open_repair = Yii::$app->formatter->asNextShift();
        $repare->status = CarRepairs::STATUS_OPEN_REPAIR;
        $repare->save();

        $this->deleteAllShiftFromNextDay($currentShift,$this->car_id);
        $this->deleteShiftsFromPeriod($currentShift,$this->car_id);
    }

    public function closeRepare():void
    {
        $nextShift = \Yii::$app->formatter->asNextShift();
        $repare = CarRepairs::find()->where(['car_id' => $this->car_id])->andWhere(['status' => CarRepairs::STATUS_OPEN_REPAIR])->one();
        $repare->date_close_repare = $nextShift;
        $repare->status = CarRepairs::STATUS_CLOSE_REPAIR;
        $repare->save();

        if($nextShift == (Yii::$app->formatter->asBeginDay(time())+(21*60*60))){
            $driverTabel = new DriverTabel();
            $driverTabel->car_id = $this->car_id;
            $driverTabel->work_date = Yii::$app->formatter->asBeginDay(time());
            $driverTabel->save();

        }

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
        $shifts = DriverTabel::find()->where(['>=', 'work_date', $dateShift])->andWhere(['car_id' => $car_id])->all();

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
    public function deleteShiftsFromPeriod(int $currentShift, int $car_id):void
    {
        $date = \Yii::$app->formatter->asBeginDay(\Yii::$app->formatter->asNextShift());
        $nextShift = \Yii::$app->formatter->asNextShift();
        $shifts = DriverTabel::find()->where(['work_date' => $date])->andWhere(['car_id' => $car_id])->one();

        $periodDay = null;
        $periodNight = null;

        if ($nextShift == ($date+(21*60*60))){
            $shifts->driver_id_night = $periodNight;
            $shifts->save();
        }
    }


    public function getAllRepairsForDataProvider(): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => CarRepairs::find()->where(['car_id' => $this->car_id])
        ]);
    }

    public function hasActiveRepair(): bool
    {
        $activeRepair = CarRepairs::find()->where(['car_id' => $this->car_id])->andWhere(['status' => CarRepairs::STATUS_OPEN_REPAIR])->one();
        return (bool)$activeRepair;
    }

}