<?php

namespace common\service\car_repare;


use app\models\CarRepairs;

class CarRepareService
{
    private $car_id;

    public function __construct($id)
    {
        $this->car_id = $id;
    }

    public function openRepare():void
    {
        $repare = new CarRepairs();
        $repare->car_id = $this->car_id;
        $repare->date_open_repair = \Yii::$app->formatter->asNextShift();
        $repare->status = CarRepairs::STATUS_OPEN_REPAIR;
        $repare->save();
    }

    public function closeRepare():void
    {
        $repare = CarRepairs::find()->where(['car_id' => $this->car_id])->andWhere(['status' => CarRepairs::STATUS_OPEN_REPAIR])->one();
        $repare->date_close_repare = \Yii::$app->formatter->asNextShift();
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
}