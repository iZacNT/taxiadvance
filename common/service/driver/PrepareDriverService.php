<?php


namespace common\service\driver;


use backend\models\DriverTabel;
use backend\models\Cars;
use common\service\constants\Constants;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;

class PrepareDriverService
{

    private $driver_id;

    public function __construct($id)
    {
        $this->driver_id = $id;
    }

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

    public function getPeriodShift(array $shift):int
    {
        if(!empty($shift)){
            if($this->driver_id == $shift[0]['driver_id_day']) {
                \Yii::debug("Дневной период");
                return Constants::PERIOD_DAY;
            }elseif ($this->driver_id == $shift[0]['driver_id_night']){
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

    public function getNumberCardPhone($shift):array
    {
        $card="Не брал";
        $phone="Не брал";
        $sum_card = 0;
        $sum_phone = 0;

            if (!empty($shift['card'])) $card = $shift['card'];
            if (!empty($shift['phone'])) $phone = $shift['phone'];
            if (!empty($shift['sum_card'])) $sum_card = $shift['sum_card'];
            if (!empty($shift['sum_phone'])) $sum_phone = $shift['sum_phone'];

        return ['card' => $card,'sum_card' => $sum_card, 'phone' => $phone, 'sum_phone' => $sum_phone];

    }

    public function getCountHoursFromOrders($allDriverOrders): int
    {

        if ($allDriverOrders){
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

    public function generateTarifTable(int $typeDay, int $period, int $carFuel, int $hours, $cars, $currentShift):string
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
                $currentShift['car_mark'] => ['selected' => true]
            ],
        ];

        $paramsButton = [
            'class' => 'btn btn-primary',
            'id' => 'calculateShift',
        ];

        if(array_key_exists('default',$currentShift)){
            $paramsButton['disabled'] = "";
        }

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
                                        Html::button('Расчитать',$paramsButton)
                                    .'</th>
                                </tr>
                                </tfoot>
                            </table>';
    }

    public function getDriverTabel(): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => DriverTabel::find()
                ->where(['driver_id_day' => $this->driver_id])
                ->orWhere(['driver_id_night' => $this->driver_id])
                ->orderBy(['work_date' => SORT_DESC])
        ]);
    }

    public function getDriverTabelArray(): ArrayDataProvider
    {
        return new ArrayDataProvider([
            'allModels' => $this->getAllShiftsInArray()
        ]);
    }

    public function getAllShiftArray(): array
    {
        return DriverTabel::find()
            ->where(['driver_id_day' => $this->driver_id])
            ->orWhere(['driver_id_night' => $this->driver_id])
            ->orderBy(['work_date' => SORT_DESC])
            ->asArray()
            ->all();
    }

    public function getCurrentShift(): array
    {
        $allOpenShifts = DriverTabel::find()
            ->where(['driver_id_day' => $this->driver_id, 'status_day_shift' => DriverTabel::STATUS_SHIFT_OPEN])
            ->orWhere(['driver_id_night' => $this->driver_id, 'status_night_shift' => DriverTabel::STATUS_SHIFT_OPEN])
            ->orderBy(['work_date' => SORT_ASC])
            ->all();

        if (empty($allOpenShifts)){
            \Yii::$app->session->setFlash('dangerShiftInTabel', 'Нет открытой смены в Табеле!!!');
        }

        return $allOpenShifts;
    }

    public function getCurrentShiftID(array $shift)
    {
        return (!empty($shift)) ? $shift[0]->id : 0;
    }

    public function validateCurrentShift(array $currentShift): array
    {
        if (empty($currentShift)){
            \Yii::$app->session->setFlash('dangerShiftInTabel', 'Нет открытых смен в Табеле на Текущий момент!!! Для более точного расчета необходимо добавить смену!<br><br>'.Html::a('Перейти в табель', ['driver-tabel/index'],['class' => 'btn btn-danger']));
            array_push($currentShift,[
                'id_shift' => 0,
                'car_id' => 0,
                'car_full_name' => 'Авто Не известен',
                'car_mark' => 'Поло',
                'car_fuel' => Constants::FUEL_GAS,
                'car_fuel_label' => Constants::getFuel()[Constants::FUEL_GAS],
                'work_date' => time(),
                'period' => Constants::PERIOD_DAY,
                'card' => null,
                'sum_card' => 0,
                'phone' => null,
                'sum_phone' => 0,
                'status_shift' => DriverTabel::labelStatusShift()[DriverTabel::STATUS_SHIFT_OPEN],
                'date_close_shift' => 0,
                'comment' => "",
                'default' => 'yes'
            ]);
        }

        return $currentShift;
    }

    public function getAllShiftsInArray(): array
    {
        $driverShift = [];
        $shifts = DriverTabel::find()
            ->where(['driver_id_day' => $this->driver_id])
            ->orWhere(['driver_id_night' => $this->driver_id])
            ->orderBy(['work_date' => SORT_DESC])
            ->all();
        foreach($shifts as $shift){
            if ($shift->driver_id_day ==  $this->driver_id){
                $driverShift =$this->addRowInArrayDay( $shift, $driverShift);
            }
            if ($shift->driver_id_night ==  $this->driver_id){
                $driverShift = $this->addRowInArrayNight( $shift, $driverShift);
            }
        }

        return $driverShift;
    }

    public function getCurrentShiftFromArray()
    {
        $openShiftsArray = [];
        $allShifts = $this->getAllShiftsInArray();
        foreach($allShifts as $shift){
            if($shift['work_date'] < time()){
                array_push($openShiftsArray, $shift);
            }
        }
        asort($openShiftsArray);


        $openShiftsArray = $this->validateCurrentShift($openShiftsArray);
        \Yii::debug($openShiftsArray, __METHOD__);
    return $openShiftsArray[0];
    }

    private function addRowInArrayDay(DriverTabel $shift, array $driverShift): array
    {
        $fullTimeShift = $shift->work_date + (9*60*60);
        array_push($driverShift,[
            'id_shift' =>$shift->id,
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
        ]);
        return $driverShift;
    }

    private function addRowInArrayNight(DriverTabel $shift, array $driverShift): array
    {
        $fullTimeShift = $shift->work_date + (21*60*60);

        array_push($driverShift,[
            'id_shift' =>$shift->id,
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
        ]);

        return $driverShift;
    }

}