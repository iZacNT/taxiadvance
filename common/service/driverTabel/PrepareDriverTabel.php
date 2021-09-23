<?php


namespace common\service\driverTabel;


use app\models\CarRepairs;
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
        for ($i=0; $i < 7; $i++) {
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
                'value' => function($data) use ($date){

                    $work_drivers = $data->getWorkDriverAtDay($data->id, $date);
                    $row = "";
                if ($work_drivers){
                    $row .= Html::a("<i class='fas fa-user-edit' style='float: right;'></i>",['update', 'id' => $work_drivers->id]);
                    $row .= Html::a("<i class='far fa-eye' style='float: right; margin-right: 10px;'></i>",['view', 'id' => $work_drivers->id]);
                    $row .= "<br />";
                    if($this->isFullDayShift($work_drivers)){
                        $row .= $this->prepareFullColumn($work_drivers->fullDayDriverName->fullName);
                    }else{
                        if ($this->verifyStatusRepair($data->id, $date, false)){
                            $row .= $this->prepareRepairColumn();
                        }else{
                            $row .= $this->prepareEmptyColumn();
                        }
                    }
                    $row .= "<hr />";
                    if($this->isFullNightShift($work_drivers)){
                        $row .= $this->prepareFullColumn($work_drivers->fullNightDriverName->fullName);
                    }else{
                        if ($this->verifyStatusRepair($data->id, $date, true)){
                            $row .= $this->prepareRepairColumn();
                        }else{
                            $row .= $this->prepareEmptyColumn();
                        }
                    }

                }else{
                    if ($this->verifyStatusRepair($data->id, $date)){
                        $row .= $this->prepareRepairColumn();
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

    public function prepareFullColumn($data)
    {
        return '<div style="font-size: 12px;">'.$data.'</div>';
    }

    public function prepareEmptyColumn()
    {
        return '<div style="font-size: 12px; color: red; font-weight: bold;">Не назначен</div>';
    }

    public function prepareRepairColumn()
    {
        return '<div style="font-size: 12px; color: red; font-weight: bold; text-transform: uppercase">На ремонте</div>';
    }

    /**
     * Проверяем на Ремонте ли автомобиль в указанную дату
     * @param $car_id int ID Авто
     * @param $date int Дата/Время начала текущей смены
     * @param $period bool Проверяем Дневной период(false) или Ночной период(true)
     * @return bool
     */
    public function verifyStatusRepair($car_id, $date, $period = false):bool
    {
        $repairs = CarRepairs::find()->where(['car_id' => $car_id])->all();
        if($period) {
            $date = \Yii::$app->formatter->asBeginDay($date)+(21*60*60);
        }
        if(!$period) {
            $date = \Yii::$app->formatter->asBeginDay($date)+(9*60*60);
        }

        foreach ($repairs as $repair){
            if ($repair->date_open_repair <= $date && empty($repair->date_close_repare) || $repair->date_close_repare > $date ){
                return true;
            }
        }
        return false;
    }

}