<?php


namespace common\service\driver;


use app\models\DriverTabel;
use backend\models\Cars;
use common\service\constants\Constants;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;

class PrepareDriverService
{

    /**
     * @param int $deposits Deposit
     * @param int $debt Debt
     * @param int $min Minimum deposit
     * @param int $max Maximum deposit
     * @param int $less Summ if $deposit < $min
     * @param int $more Summ if $min >=$deposit < $max
     * @return int Summ Depo = $less or $more
     */
    public function getDepoSumm(int $deposits, int $debt, int $min, int $max, int $less, int $more):int
    {
        $depo = 0;
        $calculate = $deposits-$debt;
        if ($calculate>=$min && $calculate<$max) { $depo = $more;}
        if ($calculate > $max) { $depo = 0;}
        if ($calculate < $min ){ $depo = $less;}

        \Yii::debug("Сумма Депо: ".$depo);

        return $depo;
    }

    public function getPeriodShift(int $driverId,array $shift):int
    {
        if(!empty($shift)){
            if($driverId == $shift[0]['driver_id_day']) {
                \Yii::debug("Дневной период");
                return Constants::PERIOD_DAY;
            }elseif ($driverId == $shift[0]['driver_id_night']){
                \Yii::debug("Ночной период");
                return Constants::PERIOD_NIGHT;
            }
        }
        return Constants::PERIOD_DAY;
    }

    public function getCarFuel($shifts):int
    {
        if (!empty($shifts)){
            return $shifts[0]->carInfo->fuel;
        }
        return Constants::FUEL_GAS;
    }

    public function getCarInfo($shift):array
    {
        if(!empty($shift)){
            $car = $shift[0]->carInfo->fullNameMark;
            $mark = $shift[0]->carInfo->mark;
            return ['car' => $car, 'mark' => $mark];
        }
        return ['car' => "", 'mark' => current((new Cars())->getAllMarks())];
    }

    public function getNumberCardPhone($period, $shift):array
    {
        $card="Не брал";
        $phone="Не брал";
        $sum_card = 0;
        $sum_phone = 0;

        if ($period == Constants::PERIOD_DAY){
            if (!empty($shift[0]->card_day)) $card = $shift[0]->card_day;
            if (!empty($shift[0]->phone_day)) $phone = $shift[0]->phone_day;
            if (!empty($shift[0]->sum_card_day)) $sum_card = $shift[0]->sum_card_day;
            if (!empty($shift[0]->sum_phone_day)) $sum_phone = $shift[0]->sum_phone_day;
        }

        if ($period == Constants::PERIOD_NIGHT){
            if (!empty($shift[0]->card_night)) $card = $shift[0]->card_night;
            if (!empty($shift[0]->card_night)) $phone = $shift[0]->card_night;
            if (!empty($shift[0]->sum_card_night)) $sum_card = $shift[0]->sum_card_night;
            if (!empty($shift[0]->sum_phone_night)) $sum_phone = $shift[0]->sum_phone_night;
        }


        return ['card' => $card,'sum_card' => $sum_card, 'phone' => $phone, 'sum_phone' => $sum_phone];

    }

    public function getCountHoursFromOrders($allDriverOrders): int
    {

        if (!empty($allDriverOrders)){
        $firstOrderTime = "";
        foreach($allDriverOrders as $order){
            if (array_key_exists('ended_at', $order)){
                $firstOrderTime = $order['ended_at'];
                break;
            }
        }
        $lastOrdertime = $allDriverOrders[array_key_last($allDriverOrders)]['ended_at'];
        $hours = strtotime($firstOrderTime)-strtotime($lastOrdertime);

        return ($hours/(60*60)>12)? 16 : 12;
        }
        return 12;
    }

    public function getCarId($shift)
    {
        return (!empty($shift[0]->car_id)) ? $shift[0]->car_id : 0;
    }

    public function generateTarifTable(int $typeDay, int $period, int $carFuel, int $hours, $cars, $mark):string
    {
        $resPeriodWork = ($typeDay == Constants::WORKING_DAY)? "checked" : "";
        $resPeriodWeekend = ($typeDay == Constants::WEEKEND_DAY)? "checked" : "";
        $periodDay = ($period == Constants::PERIOD_DAY)?  "checked" : "";
        $periodNight = ($period == Constants::PERIOD_NIGHT)?  "checked" : "";
        $fuelGas = ($carFuel == Constants::FUEL_GAS)?  "checked" : "";
        $fuelGasoline = ($carFuel == Constants::FUEL_GASOLINE)?  "checked" : "";
        $hours12 = ($hours == 12)?  "checked" : "";
        $hours16 = ($hours == 16)?  "checked" : "";
        $params = [
            'class' => 'form-control',
            'prompt' => 'Выберите марку Авто',
            'id' => 'carMarkName',
            'options' => [
                $mark => ['selected' => true]
            ],
        ];


        return '<table id="example2" class="table table-bordered table-hover dataTable dtr-inline" role="grid" aria-describedby="example2_info">
                                <thead>
                                <tr role="row"><th class="sorting sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">Тариф</th>
                                <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending">'.
            Html::dropDownList('Марки автомобилий', [],$cars, $params)
                                .'</th></tr>
                                </thead>
                                <tbody>
                                <tr class="odd">
                                    <td class="dtr-control sorting_1" tabindex="0"><div class="custom-control custom-radio">
                                            <input class="custom-control-input custom-control-input-danger" type="radio" id="workDay" name="typeDay" '.$resPeriodWork.' value="'.Constants::WORKING_DAY.'">
                                            <label for="workDay" class="custom-control-label">Будний</label>
                                        </div>
                                        </td>
                                    <td>
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input custom-control-input-danger custom-control-input-outline" type="radio" id="weekEnd" name="typeDay" '.$resPeriodWeekend.' value="'.Constants::WEEKEND_DAY.'">
                                            <label for="weekEnd" class="custom-control-label">Выходной</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="odd">
                                    <td class="dtr-control sorting_1" tabindex="0"><div class="custom-control custom-radio">
                                            <input class="custom-control-input custom-control-input-danger" type="radio" id="periodDay" name="period" '.$periodDay.' value="'.Constants::PERIOD_DAY.'">
                                            <label for="periodDay" class="custom-control-label">День</label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input custom-control-input-danger custom-control-input-outline" type="radio" id="periodNight" name="period" '.$periodNight.' value="'.Constants::PERIOD_NIGHT.'">
                                            <label for="periodNight" class="custom-control-label">Ночь</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="odd">
                                    <td class="dtr-control sorting_1" tabindex="0"><div class="custom-control custom-radio">
                                            <input class="custom-control-input custom-control-input-danger" type="radio" id="fuelGasoline" name="fuel" '.$fuelGasoline.' value="'.Constants::FUEL_GASOLINE.'">
                                            <label for="fuelGasoline" class="custom-control-label">Бензин</label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input custom-control-input-danger custom-control-input-outline" type="radio" id="fuelGas" name="fuel" '.$fuelGas.' value="'.Constants::FUEL_GAS.'">
                                            <label for="fuelGas" class="custom-control-label">Газ</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="odd">
                                    <td class="dtr-control sorting_1" tabindex="0"><div class="custom-control custom-radio">
                                            <input class="custom-control-input custom-control-input-danger" type="radio" id="hours12" name="hours" '.$hours12.' value="12">
                                            <label for="hours12" class="custom-control-label">12 Часов</label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input custom-control-input-danger custom-control-input-outline" type="radio" id="hours16" name="hours" '.$hours16.' value="16">
                                            <label for="hours16" class="custom-control-label">16 Часов</label>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th rowspan="1" colspan="1">
                                    <h3 id="loader" class="card-title float-right" style="display: none;"><i class="fas fa-2x fa-sync-alt fa-spin" style="font-size: 18px"></i> Поиск...</h3>
                                    </th>
                                    <th rowspan="1" colspan="1">'.
                                        Html::button('Расчитать',['class' => 'btn btn-primary', 'id' => 'calculateShift'])
                                    .'</th>
                                </tr>
                                </tfoot>
                            </table>';
    }

    public function getDriverTabel(DriverTabel $driverTabel, $driver_id): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => $driverTabel::find()
                ->where(['driver_id_day' => $driver_id])
                ->orWhere(['driver_id_night' => $driver_id])
                ->orderBy(['work_date' => SORT_DESC])
        ]);
    }

    public function getCurrentShift(DriverTabel $driverTabel, int $driver_id): array
    {
        $allOpenShifts = $driverTabel::find()
            ->where(['driver_id_day' => $driver_id, 'status_day_shift' => $driverTabel::STATUS_SHIFT_OPEN])
            ->orWhere(['driver_id_night' => $driver_id, 'status_night_shift' => $driverTabel::STATUS_SHIFT_OPEN])
            ->orderBy(['work_date' => SORT_ASC])
            ->all();

        return $allOpenShifts;
    }

    public function getCurrentShiftID(array $shift)
    {
        return (!empty($shift)) ? $shift[0]->id : 0;
    }

}