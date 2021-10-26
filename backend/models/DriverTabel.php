<?php

namespace app\models;

use backend\models\Cars;
use backend\models\Driver;
use backend\models\Phones;
use Yii;

/**
 * This is the model class for table "driver_tabel".
 *
 * @property int|null $id #
 * @property int|null $car_id Car
 * @property int|null $work_date Work Date
 * @property int|null $driver_id_day Draiver Day
 * @property int|null $card_day Card Day
 * @property int|null $phone_day Phone Day
 * @property int|null $driver_id_night Driver Night
 * @property int|null $card_night Card Night
 * @property int|null $phone_night Phone Night
 * @property int $sum_card_day Сумма
 * @property int $sum_phone_day Сумма
 * @property int $sum_card_night Сумма
 * @property int $sum_phone_night Сумма
 * @property int $status_day_shift [int]
 * @property int $status_night_shift [int]
 * @property int $date_close_day_shift [int]
 * @property int $date_close_night_shift [int]
 */
class DriverTabel extends \yii\db\ActiveRecord
{

    const STATUS_SHIFT_OPEN = 1;
    const STATUS_SHIFT_CLOSE = 2;

    public static function labelStatusShift(): array
    {
        return [
            self::STATUS_SHIFT_OPEN => 'Открыта',
            self::STATUS_SHIFT_CLOSE => 'Закрыта'
        ];
    }

    public $stringNameCar;
    public $stringDriverDay;
    public $stringDriverNight;
    public $stringPhoneDay;
    public $stringPhoneNight;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'driver_tabel';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['car_id', 'work_date'], 'required'],
            [['id', 'car_id', 'work_date', 'driver_id_day', 'card_day', 'sum_card_day', 'sum_phone_day',
                'sum_card_night', 'sum_phone_night', 'phone_day', 'driver_id_night', 'card_night',
                'phone_night', 'status_day_shift', 'status_night_shift', 'date_close_day_shift', 'date_close_night_shift'], 'integer'],
            [['stringNameCar', 'stringDriverDay', 'stringDriverNight', 'stringPhoneDay', 'stringPhoneNight'],'safe'],
            [['status_day_shift', 'status_night_shift'], 'default', 'value' => self::STATUS_SHIFT_OPEN]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '#',
            'car_id' => 'Автомобиль',
            'work_date' => 'Рабочая дата',
            'driver_id_day' => 'Водитель на дневной период',
            'card_day' => 'Карта',
            'phone_day' => 'Телефон',
            'driver_id_night' => 'Водитель на начной период',
            'card_night' => 'Карта',
            'phone_night' => 'Телефон',
            'stringNameCar' => 'Автомобиль',
            'stringDriverDay' => 'Дневной период',
            'stringDriverNight' => 'Ночной период',
            'sum_card_day' => 'Сумма',
            'sum_phone_day' => 'Сумма',
            'sum_card_night' => 'Сумма',
            'sum_phone_night' => 'Сумма',
            'date_close_day_shift' => 'Время закрытия смены',
            'date_close_night_shift' => 'Время закрытия смены',
            'stringPhoneDay' => '№ Тел.',
            'stringPhoneNight' => '№ Тел.'
        ];
    }

    public function getCarInfo()
    {
        return $this->hasOne(Cars::className(),['id' => 'car_id']);
    }

    public function getCarMark()
    {
        return $this->carInfo->fullNameMark;
    }

    public function getFullDayDriverName()
    {
        return $this->hasOne(Driver::className(),['id' => 'driver_id_day']);
    }

    public function getFullNightDriverName()
    {
        return $this->hasOne(Driver::className(),['id' => 'driver_id_night']);
    }

    public function getFullPhoneDayInfo()
    {
        return $this->hasOne(Phones::className(),['id' => 'phone_day']);
    }

    public function getFullPhoneNightInfo()
    {
        return $this->hasOne(Phones::className(),['id' => 'phone_night']);
    }

    public function isValidDay($date,$car_id, $oldDate)
    {
        $dayData = self::find()->where(['work_date' => $date])->andWhere(['car_id' => $car_id])->one();

        Yii::info($oldDate, 'Old Date');
        Yii::info($date, 'New Date');

        if (!empty($dayData)){
            Yii::info(!empty($dayData), 'День не пустой');
            if ($date != $oldDate){
                Yii::info($date == $oldDate, 'Result');
                return false;
            }
        }

        return true;
    }

}
