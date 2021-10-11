<?php

namespace backend\models;

use backend\models\User;
use phpDocumentor\Reflection\Types\This;
use Yii;

/**
 * This is the model class for table "driver".
 *
 * @property int $id
 * @property string|null $user_id User ID
 * @property string|null $first_name First Name
 * @property string|null $last_name Last Name
 * @property string|null $yandex_id Yandex ID
 * @property string|null $driving_license Driving License
 * @property string|null $commens Comments
 * @property string|null $passport Passport
 * @property int|null $date_of_issue Date of issue
 * @property int|null $phone Phone
 * @property string|null $who_issued_it Who issued it
 * @property string|null $city City
 * @property string|null $street Street
 * @property string|null $hous Hous
 * @property string|null $corpus Corpus
 * @property string|null $appartament Appartament
 * @property integer|null $status Status
 * @property integer|null $filial Filial
 * @property integer|null $shift_closing Shift Closing
 * @property int $current_shift [int]  Current_shift
 */
class Driver extends \yii\db\ActiveRecord
{

    public $stringShiftClosing;
    public $stringDateIssused;

    /**
     * @var string[] Status Driver
     */
    public $statusDriver = [
        '0' => 'Не активный',
        '1' => 'Работает',
        '2' => 'В черном списке',
        '3' => 'На подписании',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'driver';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name', 'filial', 'stringShiftClosing', 'yandex_id'], 'required', 'message' => '{attribute} не может быть пустым'],
            [['commens'], 'string'],
            [['user_id','date_of_issue', 'status', 'filial', 'shift_closing', 'current_shift'], 'integer'],
            [[ 'first_name', 'last_name', 'yandex_id', 'driving_license', 'passport', 'who_issued_it', 'phone', 'city', 'street', 'hous', 'corpus', 'appartament',
                'stringShiftClosing', 'stringDateIssused'], 'string', 'max' => 255],
            [['status'], 'default', 'value' => 3],
            [['shift_closing'], 'default', 'value' => time()],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
            'yandex_id' => 'Yandex ID',
            'driving_license' => 'В/У',
            'commens' => 'Комментарий',
            'passport' => '№ Паспорта',
            'date_of_issue' => 'Дата выдачи',
            'who_issued_it' => 'Кем выдан',
            'city' => 'Город',
            'street' => 'Улица',
            'hous' => 'Дом',
            'corpus' => 'Корпус',
            'appartament' => 'Квартира',
            'phone' => 'Телефон',
            'fullName' => "Полное Имя",
            'Adress' => 'Адрес',
            'status' => 'Статус',
            'filial' => 'Филиал',
            'shift_closing' => 'Смена закрыта',
            'stringShiftClosing' => 'Последнее закрытие смены',
            'stringDateIssused' => 'Дата выдачи',
            'current_shift' => 'Текущая смена',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser(){
        return $this->hasOne(User::class,['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeposit()
    {
        return $this->hasMany(Deposit::className(), ['driver_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDebt()
    {
        return $this->hasMany(Debt::className(), ['driver_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFilialData(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Filials::class,['id' => 'filial']);
    }

    /**
     * @return array
     */
    public function getAllFilials(): array
    {
        return (new Filials())->getAllFilials();
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->last_name." ".$this->first_name;
    }

    /**
     * @return string
     */
    public function getAdress()
    {
        $adres = "";

        if ($this->city){
            $adres .= $this->city.", ";
        }
        if ($this->street){
            $adres .= $this->street.", ";
        }
        if ($this->hous){
            $adres .= $this->hous;
        }
        if ($this->corpus){
            $adres .= "/".$this->corpus;
        }
        if ($this->appartament){
            $adres .= "-".$this->appartament;
        }
        if (!$adres){
            $adres = "Адрес не вписан";
        }
        return $adres;
    }

    /**
     * @return string[]
     */
    public function getStatusList()
    {
        return $this->statusDriver;
    }

}
