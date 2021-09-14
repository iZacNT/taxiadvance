<?php

namespace app\models;

use backend\models\Cars;
use backend\models\Driver;
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
 */
class DriverTabel extends \yii\db\ActiveRecord
{

    public $stringNameCar;
    public $stringDriverDay;
    public $stringDriverNight;
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
            [['id', 'car_id', 'work_date', 'driver_id_day', 'card_day', 'phone_day', 'driver_id_night', 'card_night', 'phone_night'], 'integer'],
            [['stringNameCar', 'stringDriverDay', 'stringDriverNight'],'safe'],
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
            'stringDriverDay' => 'Водитель на дневной период',
            'stringDriverNight' => 'Водитель на дневной период'
        ];
    }

    public function getCarInfo()
    {
        return $this->hasOne(Cars::className(),['id' => 'car_id']);
    }

    public function getCarMark()
    {
        return (Cars::find()->where(['id' => $this->car_id])->one())->getFullNameMark();
    }

    public function getFullDayDriverName()
    {
        return $this->hasOne(Driver::className(),['id' => 'driver_id_day']);
    }

    public function getFullNightDriverName()
    {
        return $this->hasOne(Driver::className(),['id' => 'driver_id_night']);
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

    public function getDriverShifts(int $driverId)
    {
        $startShift = Yii::$app->formatter->asBeginDay(time())-1;

        return self::find()
            ->where(['driver_id_day' => $driverId])
            ->orWhere(['driver_id_night' => $driverId])
            ->andWhere(['<','work_date', $startShift])
            ->orderBy(['work_date' => SORT_DESC])
            ->all();
    }

}
