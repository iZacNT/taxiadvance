<?php


namespace common\service\driverTabel;


use app\models\CarRepairs;
use app\models\CarSharing;
use backend\models\Cars;
use Yii;
use yii\helpers\Html;

class PrepareDriverTabel
{
    public $dateSearchFrom;

    public function __construct(int $dateSearchFrom)
    {
        $this->dateSearchFrom = $dateSearchFrom;
    }

    public function generateColumns(): array
    {
        $columns = [];
        for ($i=0; $i < 5; $i++) {
            $date = $this->dateSearchFrom;
            array_push($columns, [
                'attribute' => 'date-'.$date,
                'header'=> date('d.m.Y', $date),
                'headerOptions' => ['style' => 'font-size: 18px; font-weight: bold; text-align: center;'],
                'contentOptions' => function () use ($date) {
                    return ['style' => 'text-align: center; height: 90px;vertical-align: middle;'
                        .$this->isToday($date)];
                },
                'format' => 'raw',
                'filter' => Html::button("Поиск", ['class' => 'btn btn-primary searchDate', 'data-date' => $date]),
                'filterOptions' => ['class' => 'text-center'],
                'value' => function($data) use ($date){

                    $work_drivers = $data->getWorkDriverAtDay($data->id, $date);
                    $row = "";
                if ($work_drivers){
                    $row .= Html::a("<i class='fas fa-user-edit' style='float: right;'></i>",['update', 'id' => $work_drivers->id]);
                    $row .= Html::a("<i class='far fa-eye' style='float: right; margin-right: 10px;'></i>",['view', 'id' => $work_drivers->id]);
                    $row .= "<br />";
                    if($this->isFullDayShift($work_drivers)){
                        $row .= $this->prepareFullColumn($work_drivers->fullDayDriverName->fullName, $work_drivers->driver_id_day, $work_drivers->date_close_day_shift, $work_drivers->sum_card_day, $work_drivers->phone_day);
                    }else{
                        if ($this->verifyStatusRepair($data->id, $date)) {
                            $row .= $this->prepareRepairColumn();
                        }else if($this->verifyStatusCarSharing($data->id,$date)){
                            $row .= $this->prepareCarSharingColumn();
                        }else{
                            $row .= $this->prepareEmptyColumn();
                        }
                    }
                    $row .= "<hr />";
                    if($this->isFullNightShift($work_drivers)){
                        $row .= $this->prepareFullColumn($work_drivers->fullNightDriverName->fullName, $work_drivers->driver_id_night, $work_drivers->date_close_night_shift,  $work_drivers->sum_card_night, $work_drivers->phone_night);
                    }else{
                        if ($this->verifyStatusRepair($data->id, $date)){
                            $row .= $this->prepareRepairColumn();
                        }else if($this->verifyStatusCarSharing($data->id, $date)){
                            $row .= $this->prepareCarSharingColumn();
                        }else{
                            $row .= $this->prepareEmptyColumn();
                        }
                    }

                }else{
                    if ($this->verifyStatusRepair($data->id, $date)){
                        $row .= $this->prepareRepairColumn();
                    }else if($this->verifyStatusCarSharing($data->id, $date)){
                        $row .= $this->prepareCarSharingColumn();
                    }else{
                        $row .= Html::a("<i class='fas fa-user-plus'></i>",['create', 'carId' => $data->id, 'workDate' => $date]);
                    }
                }

                return $row;
                }
            ]);
            $this->dateSearchFrom += (24*60*60);
        } //for

        return $columns;
    } //function

    public function isToday(int $dateSearchFrom): string
    {
        return (\Yii::$app->formatter->asBeginDay(time()) == $dateSearchFrom)
            ? 'background-color: #EADCC2;' : '';
    }

    public function isFullDayShift($data)
    {
        return !empty($data->driver_id_day);
    }

    public function isFullNightShift($data)
    {
        return !empty($data->driver_id_night);
    }

    public function prepareFullColumn($nameDriver, $driver_id, $close_shift, $card_money, $phone): string
    {
        $icon = '';
        if($card_money) {
            $icon .= '<i class="fas fa-gas-pump"></i> ';
        }
        if($phone) {
            $icon .= '<i class="fas fa-mobile-alt"></i> ';
        }

        if ($close_shift){
            $icon = '<i class="fas fa-wallet" style="font-weight: bold; color: green"></i> ';
        }

        return '<div style="font-size: 16px;">'.$icon.Html::a($nameDriver, ['/driver/view', 'id' => $driver_id]).'</div>';
    }

    public function prepareEmptyColumn()
    {
        return '<div style="font-size: 12px; color: red; font-weight: bold;">Не назначен</div>';
    }

    public function prepareRepairColumn()
    {
        return '<div style="font-size: 12px; color: red; font-weight: bold; text-transform: uppercase">На ремонте</div>';
    }

    public function prepareCarSharingColumn()
    {
        return '<div style="font-size: 12px; color: red; font-weight: bold; text-transform: uppercase">Аренда</div>';
    }

    /**
     * Проверяем на Ремонте ли автомобиль в указанную дату
     * @param $car_id int ID Авто
     * @param $date int Дата/Время начала текущей смены
     * @param $period bool Проверяем Дневной период(false) или Ночной период(true)
     * @return bool
     */
    public function verifyStatusRepair($car_id, $date):bool
    {
        $repairs = CarRepairs::find()->where(['car_id' => $car_id])->all();

        foreach ($repairs as $repair){
            if ($repair->date_open_repair <= $date && (empty($repair->date_close_repare) || $repair->date_close_repare >= $date )){
                return true;
            }
        }
        return false;
    }

    private function verifyStatusCarSharing($car_id, $date): bool
    {

        $carSharings = CarSharing::find()->where(['car_id' => $car_id])->all();
        foreach($carSharings as $carSharing)
        {
            if ($carSharing->date_start <= $date && $date <= $carSharing->date_stop){
                return true;
            }
        }
        return false;
    }

}