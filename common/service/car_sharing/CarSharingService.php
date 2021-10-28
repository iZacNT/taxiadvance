<?php
namespace common\service\car_sharing;

use app\models\CarSharing;
use backend\models\Cars;
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

    public function isValidDate(int $date)
    {
        $sharings = CarSharing::find()->where(['car_id' => $this->car_id])->all();
        foreach($sharings as $sharing){
            if ($sharing->date_start <= $date && $date <= $sharing->date_stop){
                return false;
            }
        }
        return true;
    }

}