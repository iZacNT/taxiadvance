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
class Debt extends \yii\db\ActiveRecord
{



    public $debtReasons = [
        '0' => 'ДТП',
        '1' => 'Штраф ГИБДД',
        '2' => 'Штраф стоянка',
        '3' => 'Срыв смены',
        '4' => 'Ремонт',
    ];

    public $stringDateReason;
    public $stringDateDtp;
    public $stringDatePay;
    public $stringNameCar;

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
            [['driver_id', 'dette', 'back', 'reason', 'car_id', 'date_reason', 'date_dtp', 'payable', 'date_pay'], 'integer'],
            [['comment', 'stringDateReason', 'stringDateDtp', 'stringDatePay'], 'string'],
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
            'geo_dtp' => 'Место ДТП',
            'date_dtp' => 'Датат ДТП',
            'payable' => 'Оплачено',
            'date_pay' => 'Дата платежа',
            'stringDateReason' => 'Дата нарушения',
            'stringDateDtp' => 'Дата ДТП',
            'stringDatePay' => 'Дата платежа',
        ];
    }

    public function getDriver(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Driver::className(),['id' => 'driver_id']);
    }

    public function getCar(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Cars::className(),['id' => 'car_id']);
    }

}
