<?php

namespace common\service\driver;

use backend\models\DriverTabel;
use common\service\constants\Constants;
use yii\helpers\Html;

class PrepareRangeShift
{
    public function prepareRangeShifts(array $data): string
    {
        $from = strtotime($data['from']);
        $to = strtotime($data['to']);
        $html = '<table class="table table-striped table-bordered">';

        for($i=$from; $i<=$to; $i+=86400){
            \Yii::debug(\Yii::$app->formatter->asDate($i),__METHOD__);
            $shiftAtOtherCar = DriverTabel::find()->where(['driver_id_day' => $data['driverId']])->orWhere(['driver_id_night' => $data['driverId']])->andWhere(['work_date' => $i])->one();
            \Yii::debug($shiftAtOtherCar,__METHOD__);
            $shift = DriverTabel::find()->where(['car_id' => $data['carId']])->andWhere(['work_date' => $i])->one();
            if(empty($shift)){
                $html .= $this->prepareFreeShift($i);
            }else{
                $html .= $this->prepareBusyShift($i, $shift, $shiftAtOtherCar);
            }
        }

        return $html.="</table>";
    }

    private function prepareFreeShift(int $date)
    {
        return "<tr class='rowSelectedDate'>
                    <td>
                        "
                .
                Html::checkbox('date[]',false,['name'=>'date[]', 'value' => $date, 'label' => \Yii::$app->formatter->asDate($date)])
            .
        "
                    </td>
                    <td>
                    ".
                Html::radioList($date,Constants::PERIOD_DAY,[Constants::PERIOD_DAY=>"День", Constants::PERIOD_NIGHT => 'Ночь'])
            ."
</td>
                </tr>";
    }

    private function prepareBusyShift(int $date, DriverTabel $shift, $shiftOtherCar): string
    {
        if (empty($shiftOtherCar) || $shiftOtherCar->car_id === $shift->car_id){
            return "<tr class='rowSelectedDate' data-busy='1' style='background: #ffc107; color: #0a0a0a'>
                        <td>
                            "
                .
                Html::checkbox('date[]',false,['name'=>'date[]', 'value' => $date, 'label' => \Yii::$app->formatter->asDate($date)])
                .
                "
                        </td>
                        <td>
                        ".
                Html::radioList($date,Constants::PERIOD_DAY,[Constants::PERIOD_DAY=> (empty($shift->driver_id_day))? "День" : "День (".$shift->fullDayDriverName->fullName.")", Constants::PERIOD_NIGHT => (empty($shift->driver_id_night))? "Ночь" : "Ночь (".$shift->fullNightDriverName->fullName.")"])
                ."
                </td>
                    </tr>";
        }else{
            return "<tr style='background: red; color: #ffffff;'><td colspan='2' style='align-content: center'> Водитель установлен на другой автомобиль: ".$shift->carInfo->fullNameMark."!</td></tr>";
        }
    }

    public function saveRange(array $postData): bool
    {
        if (isset($postData['rowsData'])) {
            $rowsData = $postData['rowsData'];
            $rangeData = $postData['rangeData'];

            foreach ($rowsData as $data) {
                $shift = DriverTabel::find()->where(['work_date' => $data['date']])->andWhere(['car_id' => $rangeData['carId']])->one();
                if (empty($shift)) {
                    $newShift = new DriverTabel();
                    $newShift->work_date = $data['date'];
                    $newShift->car_id = $rangeData['carId'];
                    if ($data['period'] == Constants::PERIOD_DAY) {
                        $newShift->driver_id_day = $rangeData['driverId'];
                    } else {
                        $newShift->driver_id_night = $rangeData['driverId'];
                    }
                    $newShift->save();
                } else {
                    if ($data['period'] == Constants::PERIOD_DAY) {
                        if ($shift->driver_id_night != $rangeData['driverId']) {
                            $shift->driver_id_day = $rangeData['driverId'];
                        }
                    } else {
                        if ($shift->driver_id_day != $rangeData['driverId']) {
                            $shift->driver_id_night = $rangeData['driverId'];
                        }
                    }
                    $shift->save();
                }
            }
            return true;
        }
        return false;
    }
}