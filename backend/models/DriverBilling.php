<?php

namespace app\models;

use backend\models\Driver;

/**
 * This is the model class for table "driver_billing".
 *
 * @property int $id #
 * @property int|null $driver_id Driver
 * @property int|null $date_billing Date
 * @property float|null $balance_yandex Yndex balance
 * @property int|null $bonus_yandex Bonus Yandex
 * @property string|null $car_mark Car Mark
 * @property int|null $fuel Fuel
 * @property int|null $period Period
 * @property int|null $type_day Type Day
 * @property int|null $input_amount Input Amount
 * @property int|null $depo depo
 * @property int|null $debt_from_shift Debt from Shift
 * @property int|null $car_wash Car Wash
 * @property int|null $car_fuel_summ Car Fuel Summ
 * @property int|null $car_phone_summ Car Phone Summ
 * @property int|null $hours Hours
 * @property int|null $billing Billing
 * @property int|null $percent_park Percent Park
 * @property int|null $percent_driver Percent Driver
 * @property int|null $summ_park Summ Park
 * @property int|null $summ_driver Summ Driver
 * @property int|null $plan
 */
class DriverBilling extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'driver_billing';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['driver_id', 'date_billing', 'bonus_yandex', 'fuel', 'period', 'type_day', 'input_amount', 'depo', 'debt_from_shift', 'car_wash', 'car_fuel_summ', 'car_phone_summ', 'hours', 'billing', 'percent_park', 'percent_driver', 'summ_park', 'summ_driver', 'plan'], 'integer'],
            [['balance_yandex'], 'number'],
            [['car_mark'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '№',
            'driver_id' => 'Водитель',
            'date_billing' => 'Дата',
            'balance_yandex' => 'Баланс',
            'bonus_yandex' => 'Бонус',
            'car_mark' => 'Марка',
            'fuel' => 'Топливо',
            'period' => 'Период',
            'type_day' => 'Тип дня',
            'input_amount' => 'Сумма для расчета',
            'depo' => 'Депо',
            'debt_from_shift' => 'Долг по смене',
            'car_wash' => 'Мойка',
            'car_fuel_summ' => 'Топливо',
            'car_phone_summ' => 'Телефон',
            'hours' => 'Часы работы',
            'billing' => 'Расчет',
            'percent_park' => 'Процент парка',
            'percent_driver' => 'Процент водителя',
            'summ_park' => 'Сумма парка',
            'summ_driver' => 'Сумма водителя',
            'plan' => 'План',
        ];
    }

    public function getDriverInfo()
    {
        return $this->hasOne(Driver::className(),['id' => 'driver_id']);
    }
}
