<?php

namespace backend\models;

use backend\models\Cars;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "car_repairs".
 *
 * @property int $id
 * @property int|null $car_id Авто
 * @property int|null $date_open_repair Дата начала ремонта
 * @property int|null $date_close_repare Дата окончания ремонта
 * @property int|null $status Статус
 * @property Cars $car
 */
class CarRepairs extends \yii\db\ActiveRecord
{

    public $stringNameCar;
    public $stringDateOpenRepair;
    public $stringDateCloseRepair;

    const STATUS_OPEN_REPAIR = 1;
    const STATUS_CLOSE_REPAIR = 2;

    public function getStatusLabel():array
    {
       return [
        self::STATUS_OPEN_REPAIR => 'На ремонте',
        self::STATUS_CLOSE_REPAIR => 'Ремонт окончен'
       ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'car_repairs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['car_id', 'date_open_repair', 'date_close_repare', 'status'], 'integer'],
            [['car_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cars::className(), 'targetAttribute' => ['car_id' => 'id']],
            [['status'], 'default', 'value' => self::STATUS_OPEN_REPAIR],
            [['stringNameCar', 'stringDateOpenRepair', 'stringDateCloseRepair'], 'safe'],
            ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'car_id' => 'Авто',
            'date_open_repair' => 'Дата начала ремонта',
            'date_close_repare' => 'Дата окончания ремонта',
            'status' => 'Статус',
            'carFullName' => 'Авто',
            'statusType' => 'Статус',
            'stringNameCar' => 'Авто',
            'stringDateOpenRepair' => 'Дата начала ремонта',
            'stringDateCloseRepair' => 'Дата окончания ремонта'
        ];
    }

    /**
     * Gets query for [[Car]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCar()
    {
        return $this->hasOne(Cars::className(), ['id' => 'car_id']);
    }

    public function getCarFullName() {
        return $this->car->fullNameMark;
    }

    public function getStatusType(): string
    {
        return self::getStatusLabel()[$this->status];
    }

    public static function getOpenRepairsForAutocomplete($filial = ''): array
    {
        $result = [];
        $repairs = self::find()
            ->where(['status' => self::STATUS_OPEN_REPAIR])
            ->andFilterWhere(['filial' => $filial])
            ->all();

        foreach($repairs as $repair){
            array_push($result,[
               'value' => $repair->id.": ".$repair->car->fullNameMark." ".Yii::$app->formatter->asDate($repair->date_open_repair) ,
               'label' => $repair->id.": ".$repair->car->fullNameMark." ".Yii::$app->formatter->asDate($repair->date_open_repair),
               'id' =>  $repair->id
            ]);
        }

        return $result;
    }

}
