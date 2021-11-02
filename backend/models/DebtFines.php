<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "debt".
 *
 * @property int $id #
 * @property int|null $driver_id Driver
 * @property int|null $dette Debt
 * @property int|null $back Back
 * @property int|null $reason Reason
 * @property string|null $comment Comment
 * @property int|null $car_id Car
 * @property int|null $date_reason Date
 * @property string|null $regulation Regulation
 * @property string|null $geo_dtp Geo
 * @property int|null $date_dtp Date DTP
 * @property int|null $payable Payable
 * @property int|null $date_pay Date Pay
 */
class DebtFines extends \yii\db\ActiveRecord
{

    public $stringDateReason;
    public $stringDateDtp;
    public $stringDatePay;
    public $stringNameCar;
    public $stringNameDriver;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'debt';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['driver_id', 'stringNameDriver'],'required'],
            [['driver_id', 'dette', 'back', 'reason', 'car_id', 'date_reason', 'date_dtp', 'payable', 'date_pay'], 'integer'],
            [['comment', 'stringDateReason', 'stringDateDtp', 'stringDatePay', 'stringNameDriver'], 'string'],
            [['regulation', 'geo_dtp'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '#',
            'driver_id' => 'Водитель',
            'dette' => 'Долг',
            'back' => 'Вернул',
            'reason' => 'Причина',
            'comment' => 'Комментарий',
            'car_id' => 'Авто',
            'date_reason' => 'Дата',
            'regulation' => 'Постановление',
            'geo_dtp' => 'Место штрафа',
            'date_dtp' => 'Дата',
            'payable' => 'Оплата',
            'date_pay' => 'Дата платежа',
            'stringDateReason' => 'Дата нарушения',
            'stringNameCar' => 'Автомобиль',
            'stringDateDtp' => 'Дата нарушения',
            'stringDatePay' => 'Дата оплаты',
            'carFullName' => 'Автомобиль',
            'driverFullName' => 'Водитель'
        ];
    }

    public function getDriverInfo(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Driver::class,['id' => 'driver_id']);
    }

    public function getDriverFullName() {
        return $this->driverInfo->fullName;
    }

    public function getCarInfo(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Cars::class,['id' => 'car_id']);
    }

    public function getCarFullName() {
        return $this->carInfo->fullNameMark;
    }
}
