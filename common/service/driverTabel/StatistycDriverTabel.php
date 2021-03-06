<?php

namespace common\service\driverTabel;

use backend\models\CarRepairs;
use backend\models\CarSharing;
use backend\models\DriverBilling;
use backend\models\DriverTabel;
use backend\models\Cars;
use common\service\driver\BillingDeleteService;
use yii\helpers\ArrayHelper;

class StatistycDriverTabel
{

    public function generateStatisticByDay($date): string
    {
        $arrData = $this->getArrStatisticByDay($date);
        $html = $this->prepareHtmlForCars($arrData['driverData'],$arrData['carsData']);

        return $html;
    }

    public function generateStatisticByDayForDashboard($date): array
    {
        $arrData = $this->getArrStatisticByDay($date-(5*60*60));
        return $this->prepareHtmlForDashboard($arrData['driverData'],$arrData['carsData']);
    }

    public function getArrStatisticByDay($date): array
    {
        $driversData = $this->generateDriverStatistic($date);
        $carsData = $this->generateCarsStatistic($date);


        return ['driverData' => $driversData,'carsData' => $carsData];
    }

    private function generateDriverStatistic($date): array
    {

        $beginDay = $date;
        $endDay = $date+(24*60*60);

        $tabel = DriverTabel::find()
            ->where(['>=','work_date', $beginDay])
            ->andWhere(['<', 'work_date', $endDay])
            ->asArray()
            ->all();
        $driverAtShift = $this->driverAtShift($tabel);

        $driverGetOnFuelDay = $this->driverGetOnFuel($driverAtShift['day_drivers']);
        $driverGetOnFuelNight = $this->driverGetOnFuel($driverAtShift['night_drivers']);
        $driverGetPhoneDay = $this->driverGetPhone($driverAtShift['day_drivers']);
        $driverGetPhoneNight = $this->driverGetPhone($driverAtShift['night_drivers']);
        $driverCloseShiftDay = $this->closeShift($driverAtShift['day_drivers']);
        $driverCloseShiftNight = $this->closeShift($driverAtShift['night_drivers']);

        return [
            'driverAtShift' => $driverAtShift,
            'driverFuelDay' => $driverGetOnFuelDay,
            'driverFuelNight' => $driverGetOnFuelNight,
            'driverPhoneDay' => $driverGetPhoneDay,
            'driverPhoneNight' => $driverGetPhoneNight,
            'driverCloseShiftDay' => $driverCloseShiftDay,
            'driverCloseShiftNight' => $driverCloseShiftNight,
        ];
    }

    private function driverAtShift(array $tabel): array
    {
        $dayPeriod = [];
        $nightPeriod = [];

        foreach($tabel as $item){
            if (!empty($item['driver_id_day'])){
                $dayPeriod[] = [
                    'driver_id' => $item['driver_id_day'],
                    'card' => $item['card_day'],
                    'phone' => $item['phone_day'],
                    'sum_card' => $item['sum_card_day'],
                    'sum_phone' => $item['sum_phone_day'],
                    'status' => $item['status_day_shift'],
                    'date_close' => $item['date_close_day_shift'],
                    'billing' => $item['billing_id_day'],
                ];
            }
            if (!empty($item['driver_id_night'])){
                $nightPeriod[] = [
                    'driver_id' => $item['driver_id_night'],
                    'card' => $item['card_night'],
                    'phone' => $item['phone_night'],
                    'sum_card' => $item['sum_card_night'],
                    'sum_phone' => $item['sum_phone_night'],
                    'status' => $item['status_night_shift'],
                    'date_close' => $item['date_close_night_shift'],
                    'billing' => $item['billing_id_night'],
                ];
            }
        }

        return ['day_drivers' => $dayPeriod, 'count_day' => $this->countData($dayPeriod), 'night_drivers' => $nightPeriod, 'count_night' => $this->countData($nightPeriod)];
    }

    private function countData(array $data): int
    {
        return count($data);
    }

    private function driverGetOnFuel($driverAtShift): array
    {
        $drivers = [];
        foreach($driverAtShift as $item)
        {
            if(!empty($item['sum_card'])){
                $drivers[] = $item['driver_id'];
            }
        }

        return ['drivers' => $drivers, 'count' => $this->countData($drivers)];
    }

    private function driverGetPhone($driverAtShift): array
    {
        $drivers = [];
        foreach($driverAtShift as $item)
        {
            if(!empty($item['phone'])){
                $drivers[] = $item['driver_id'];
            }
        }

        return ['drivers' => $drivers, 'count' => $this->countData($drivers)];
    }

    private function closeShift($driverAtShift): array
    {
        $drivers = [];
        foreach($driverAtShift as $item)
        {
            if(!empty($item['date_close'])){
                $drivers[] = $item['driver_id'];
            }
        }

        return ['drivers' => $drivers, 'count' => $this->countData($drivers)];
    }


    public function generateCarsStatistic($date): array
    {
        $empty = [
            'busyDay' => [],
            'busyNight' => [],
            'inRepair' =>[
                'dayRepare' => [],
                'nightRepare' => [],
            ],
            'inShared' => [
                'dayShared' => [],
                'nightShared' => [],
            ],
            'inEmpty' => [
                'dayEmpty' => [],
                'nightEmpty' => []
            ]
        ];
        $emptyFullDay = [
            'inRepair' =>[],
            'inShared' => [],
            'inEmpty' => []
        ];

        $allWorkCars = Cars::find()->where(['status' => Cars::STATUS_WORK])->all();

        $haveShift = DriverTabel::find()
                ->where(['>=', 'work_date', $date])
                ->andWhere(['<', 'work_date', ($date+(24*60*60)-1)])
                ->all();
            foreach ($haveShift as $shift){
                $empty['busyDay'] = $this->seeDriver($shift, $shift->driver_id_day, $empty['busyDay']);
                $empty['busyNight'] = $this->seeDriver($shift, $shift->driver_id_night, $empty['busyNight']);
                $empty = $this->emptyDriver($shift, $empty);
            }
            $allIdCars = ArrayHelper::getColumn($allWorkCars, 'id');

            foreach ($haveShift as $shift){
                $index = array_search($shift->car_id, $allIdCars);
                unset($allIdCars[$index]);
            }

            foreach($allIdCars as $car){
                $emptyFullDay = $this->emptyFullDay($car, $date,$emptyFullDay);
            }

        foreach($emptyFullDay['inRepair'] as $repairCarId){
            $empty['inRepair']['dayRepare'][] = $repairCarId;
            $empty['inRepair']['nightRepare'][] = $repairCarId;
        }
        foreach($emptyFullDay['inShared'] as $sharedCarId){
            $empty['inShared']['dayShared'][] = $sharedCarId;
            $empty['inShared']['nightShared'][] = $sharedCarId;
        }
        foreach($emptyFullDay['inEmpty'] as $emptyCarId){
            $empty['inEmpty']['dayEmpty'][] = $emptyCarId;
            $empty['inEmpty']['nightEmpty'][] = $emptyCarId;
        }

        return $empty;
    }

    /**
     * @param DriverTabel $shift ??????????
     * @param int $driver ????????????????
     * @param array $result ???????? ?????????????? ????????????
     * @return array $result
     */
    public function seeDriver(DriverTabel $shift, $driver, $result): array
    {
        if(!empty($driver)){
            $result[] = $shift->car_id;
        }

        return $result;
    }

    /**
     * ?????????????????? ?????????? ???? ????????????????, ???????? ???????????????? ?????? ?? ??????????????, ???? ?????????????? ?????? ????????, ?? ??????????????, ?? ????????????, ?? ??????????????.
     * @param DriverTabel $shift ??????????
     * @param array $result ???????? ?????????????? ????????????
     * @return array $result
     */
    public function emptyDriver(DriverTabel $shift, $result): array
    {

        if(empty($shift->driver_id_day)){
            $result['inRepair']['dayRepare'] = $this->inRepair($shift->car_id, $shift->work_date+(9*60*60), $result['inRepair']['dayRepare']);
            $result['inShared']['dayShared'] = $this->inShared($shift->car_id, $shift->work_date+(9*60*60), $result['inShared']['dayShared']);
            $mergeCars = array_merge($result['inRepair']['dayRepare'], $result['inShared']['dayShared']);
            $result['inEmpty']['dayEmpty'] = $this->inEmpty($shift->car_id, $mergeCars, $result['inEmpty']['dayEmpty']);
        }
        if(empty($shift->driver_id_night)){
            $result['inRepair']['nightRepare'] = $this->inRepair($shift->car_id, $shift->work_date+(21*60*60), $result['inRepair']['nightRepare']);
            $result['inShared']['nightShared'] = $this->inShared($shift->car_id, $shift->work_date+(21*60*60), $result['inShared']['nightShared']);
            $mergeCars = array_merge($result['inRepair']['nightRepare'], $result['inShared']['nightShared']);
            $result['inEmpty']['nightEmpty'] = $this->inEmpty($shift->car_id, $mergeCars, $result['inEmpty']['nightEmpty']);
        }

        return $result;
    }

    /**
     * ?????????????????? ?? ?????????????? ???? ????????
     * @param int $car_id
     * @param int $date //???????? ??????????
     * @param array $result ???????????? ???????? ?????????????? ????????????
     * @return array $result
     */
    private function inRepair(int $car_id, int $date, array $result): array
    {
        $repairs = CarRepairs::find()->where(['car_id' => $car_id])->all();
        foreach($repairs as $repair)
        {
            if($repair->date_open_repair <= $date && (empty($repair->date_close_repare) || $date < $repair->date_close_repare)){
                $result[] = $repair->car_id;
            }
        }
        return $result;
    }

    /**
     * ?????????????????? ?? ???????????? ???? ????????
     * @param int $car_id
     * @param $date //???????? ??????????
     * @param array $result ???????????? ???????? ?????????????? ????????????
     * @return array $result
     */
    private function inShared(int $car_id, $date, array $result): array
    {
        $sharings = CarSharing::find()->where(['car_id' => $car_id])->all();
        foreach($sharings as $sharing)
        {
            if($sharing->date_start <= $date && (empty($sharing->date_stop) || $date < $sharing->date_stop)){
                $result[] = $sharing->car_id;
            }
        }
        return $result;
    }

    /**
     * @param DriverTabel $shift
     * @param $mergeCars //???????????? ?????????????????????? ???????????????????????? ?? ?????????????? ?? ????????????
     * @param $result //???????????? ???????? ?????????????? ??????????????????
     */
    private function inEmpty(int $car_id, $mergeCars, $result)
    {
        if(in_array($car_id, $mergeCars)==false)
        {
            $result[] = $car_id;
        }
        return $result;
    }

    private function emptyFullDay(int $car,$date, array $result): array
    {
        $result['inRepair'] = $this->inRepair($car, $date, $result['inRepair']);
        $result['inShared'] = $this->inShared($car, $date, $result['inShared']);
        $mergeCars = array_merge($result['inRepair'], $result['inShared']);
        $result['inEmpty'] = $this->inEmpty($car, $mergeCars, $result['inEmpty']);

        return $result;
    }

    private function prepareHtmlForCars(array $driversData, array $empty): string
    {
        return '<div id="accordion">
                  <div class="card card-primary">
                    <div class="card-header">
                      <h4 class="card-title w-100">
                        <a class="d-block w-100" data-toggle="collapse" href="#collapseOne" aria-expanded="true">
                          ???????????????????? ?? ??????????????????:
                        </a>
                      </h4>
                    </div>
                    <div id="collapseOne" class="collapse show" data-parent="#accordion" style="">
                      <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                  <div class="card-header">
                                    <h3 class="card-title">
                                      ?????????????? ??????????
                                    </h3>
                                  </div>
                                  <!-- /.card-header -->
                                  <div class="card-body">
                                    <dl class="row">
                                      <dt class="col-sm-8">?????????????????? ???? ??????????:</dt>
                                      <dd class="col-sm-4">'.$this->countData($driversData['driverAtShift']['day_drivers']).'</dd>
                                      <dt class="col-sm-8">?????????? ???? ??????????????:</dt>
                                      <dd class="col-sm-4">'.$this->countData($driversData['driverFuelDay']['drivers']).'</dd>
                                      <dt class="col-sm-8">?????????? ??????????????:</dt>
                                      <dd class="col-sm-4">'.$this->countData($driversData['driverPhoneDay']['drivers']).'</dd>
                                      <dt class="col-sm-8">???????????????? ????????:</dt>
                                      <dd class="col-sm-4">'.$this->countData($driversData['driverCloseShiftDay']['drivers']).'</dd>
                                    </dl>
                                  </div>
                                  <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                              </div>
                              <div class="col-md-6">
                                <div class="card">
                                  <div class="card-header">
                                    <h3 class="card-title">
                                      ???????????? ??????????
                                    </h3>
                                  </div>
                                  <!-- /.card-header -->
                                  <div class="card-body">
                                    <dl class="row">
                                      <dt class="col-sm-8">?????????????????? ???? ??????????:</dt>
                                      <dd class="col-sm-4">'.$this->countData($driversData['driverAtShift']['night_drivers']).'</dd>
                                      <dt class="col-sm-8">?????????? ???? ??????????????:</dt>
                                      <dd class="col-sm-4">'.$this->countData($driversData['driverFuelNight']['drivers']).'</dd>
                                      <dt class="col-sm-8">?????????? ??????????????:</dt>
                                      <dd class="col-sm-4">'.$this->countData($driversData['driverPhoneNight']['drivers']).'</dd>
                                      <dt class="col-sm-8">???????????????? ????????:</dt>
                                      <dd class="col-sm-4">'.$this->countData($driversData['driverCloseShiftNight']['drivers']).'</dd>
                                    </dl>
                                  </div>
                                  <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                              </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="card card-danger">
                    <div class="card-header">
                      <h4 class="card-title w-100">
                        <a class="d-block w-100 collapsed" data-toggle="collapse" href="#collapseTwo" aria-expanded="false">
                            ???????????????????? ?? ??????????????????????:
                        </a>
                      </h4>
                    </div>
                    <div id="collapseTwo" class="collapse" data-parent="#accordion" style="">
                      <div class="card-body">
                          <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                  <div class="card-header">
                                    <h3 class="card-title">
                                      ?????????????? ??????????
                                    </h3>
                                  </div>
                                  <!-- /.card-header -->
                                  <div class="card-body">
                                    <dl class="row">
                                      <dt class="col-sm-8">?????????????????? ????????????????????:</dt>
                                      <dd class="col-sm-4">'.$this->countData($empty['inEmpty']['dayEmpty']).'</dd>
                                      <dt class="col-sm-8">???? ??????????:</dt>
                                      <dd class="col-sm-4">'.$this->countData($empty['busyDay']).'</dd>
                                      <dt class="col-sm-8">?????????????????????? ?? ??????????????:</dt>
                                      <dd class="col-sm-4">'.$this->countData($empty['inRepair']['dayRepare']).'</dd>
                                      <dt class="col-sm-8">?????????????????????? ?? ????????????:</dt>
                                      <dd class="col-sm-4">'.$this->countData($empty['inShared']['dayShared']).'</dd>
                                    </dl>
                                  </div>
                                  <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                              </div>
                              <div class="col-md-6">
                                <div class="card">
                                  <div class="card-header">
                                    <h3 class="card-title">
                                      ???????????? ??????????
                                    </h3>
                                  </div>
                                  <!-- /.card-header -->
                                  <div class="card-body">
                                    <dl class="row">
                                      <dt class="col-sm-8">?????????????????? ????????????????????:</dt>
                                      <dd class="col-sm-4">'.$this->countData($empty['inEmpty']['nightEmpty']).'</dd>
                                      <dt class="col-sm-8">???? ??????????:</dt>
                                      <dd class="col-sm-4">'.$this->countData($empty['busyNight']).'</dd>
                                      <dt class="col-sm-8">?????????????????????? ?? ??????????????:</dt>
                                      <dd class="col-sm-4">'.$this->countData($empty['inRepair']['nightRepare']).'</dd>
                                      <dt class="col-sm-8">?????????????????????? ?? ????????????:</dt>
                                      <dd class="col-sm-4">'.$this->countData($empty['inShared']['nightShared']).'</dd>
                                    </dl>
                                  </div>
                                  <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                              </div>
                          </div>
                      </div>
                    </div>
                  </div>
                </div>';
    }

    private function prepareHtmlForDashboard($driverData, $carsData): array
    {
        return [
            'driversData' => $this->prepareHtmlForDashboardDrivers($driverData),
            'carsData' => $this->prepareHtmlForDashboardCars($carsData)
        ];
    }

    private function prepareHtmlForDashboardDrivers($driverData): string
    {
        return '
            <tr>
                <td>???? ??????????</td>
                <td align="center">'.$driverData["driverAtShift"]["count_day"].'</td>
                <td align="center">'.$driverData["driverAtShift"]["count_night"].'</td>
            </tr>
            <tr>
                <td>?????????? ???? ??????????????</td>
                <td align="center">'.$driverData["driverFuelDay"]["count"].'</td>
                <td align="center">'.$driverData["driverFuelNight"]["count"].'</td>
            </tr>
            <tr>
                <td>?????????? ???? ??????????????</td>
                <td align="center">'.$driverData["driverPhoneDay"]["count"].'</td>
                <td align="center">'.$driverData["driverPhoneNight"]["count"].'</td>
            </tr>
            <tr>
                <td>???????????????? ????????</td>
                <td align="center">'.$driverData["driverCloseShiftDay"]["count"].'</td>
                <td align="center">'.$driverData["driverCloseShiftNight"]["count"].'</td>
            </tr>';
    }

    private function prepareHtmlForDashboardCars($carsData)
    {
        return '
            <tr>
                <td>???? ??????????</td>
                <td align="center">'.count($carsData["busyDay"]).'</td>
                <td align="center">'.count($carsData["busyNight"]).'</td>
            </tr>
            <tr>
                <td>??????????????????</td>
                <td align="center">'.count($carsData["inEmpty"]["dayEmpty"]).'</td>
                <td align="center">'.count($carsData["inEmpty"]["nightEmpty"]).'</td>
            </tr>
            <tr>
                <td>?? ??????????????</td>
                <td align="center">'.count($carsData["inRepair"]["dayRepare"]).'</td>
                <td align="center">'.count($carsData["inRepair"]["dayRepare"]).'</td>
            </tr>
            <tr>
                <td>?? ????????????</td>
                <td align="center">'.count($carsData["inShared"]["dayShared"]).'</td>
                <td align="center">'.count($carsData["inShared"]["nightShared"]).'</td>
            </tr>';
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function getStatisticCurrentMonth(): array
    {
        $driverByDay = $this->getDriversByDay();
        return $this->preparedArrForBarChart($driverByDay);
    }

    public function getDriversByDay(): array
    {
        $firstDayCurrentMonth = \Yii::$app->formatter->asTimestamp(date('Y-m-01'));
        $lastDayCurrentMonth = \Yii::$app->formatter->asTimestamp(date('Y-m-t'));
        $arrDriverAtShiftByDate = [];
        for($i=$firstDayCurrentMonth; $i<=$lastDayCurrentMonth; $i+=(24*60*60)){
            $beginDay = $i;
            $endDay = $i+(24*60*60);

            $tabel = DriverTabel::find()
                ->where(['>=','work_date', $beginDay])
                ->andWhere(['<', 'work_date', $endDay])
                ->asArray()
                ->all();

            $summFromBilling = $this->getSummFromBilling($this->driverAtShift($tabel));

            $arrDriverAtShiftByDate[] = [ $i => $this->driverAtShift($tabel), 'sumFromBillingAtDay' => $summFromBilling];
        }
        return $arrDriverAtShiftByDate;
    }

    private function getSummFromBilling(array $driverAtShift): array
    {
        $sumDay = 0;
        $sumNight = 0;
        if($driverAtShift['count_day']){
            foreach($driverAtShift['day_drivers'] as $driver){
                $sum = DriverBilling::find()->where(['id' => $driver['billing']])->one();
                $sumDay+= ($sum) ? $sum->summ_park : 0;
            }
        }
        if($driverAtShift['count_night']){
            foreach($driverAtShift['night_drivers'] as $driver){
                $sum = DriverBilling::find()->where(['id' => $driver['billing']])->one();
                $sumNight+=($sum) ? $sum->summ_park : 0;
            }
        }

        return ['sumDay' => $sumDay, 'sumNight' => $sumNight];
    }

    private function preparedArrForBarChart(array $driverByDay): array
    {
        $day = 0;
        $countDay = [];
        $dayBarChart = [];
        $nightBarChart = [];
        $sumByDayBarChart = [];
        for($i=0; $i<count($driverByDay); $i++){
            $countDay[] = $i+1  ;
            $dayBarChart[] = $driverByDay[$i]['sumFromBillingAtDay']['sumDay'];
            $nightBarChart[] = $driverByDay[$i]['sumFromBillingAtDay']['sumNight'];
            $sumByDayBarChart[] = $driverByDay[$i]['sumFromBillingAtDay']['sumDay']+$driverByDay[$i]['sumFromBillingAtDay']['sumNight'];
        }

        return [
            'countDay' =>  $countDay,
            'dayBarCart' => $dayBarChart,
            'nightBarChart' => $nightBarChart,
            'sumByDayBarChart' => $sumByDayBarChart,
            'dayBarCartAmount' => $this->calculateAmount($dayBarChart),
            'nightBarChartAmount' => $this->calculateAmount($nightBarChart),
            'sumByDayBarChartAmount' => $this->calculateAmount($sumByDayBarChart)
        ];
    }

    private function calculateAmount($array)
    {
        $amount = 0;
        foreach($array as $item){
            $amount+=$item;
        }

        return $amount;
    }

}