<?php

namespace backend\models;

use app\models\CarRepairs;
use app\models\CarSharing;
use app\models\DriverTabel;
use backend\models\Filials;
use common\service\constants\Constants;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "cars".
 *
 * @property int $id
 * @property string|null $mark Mark
 * @property int|null $year Year
 * @property string|null $number Number
 * @property string|null $vin VIN
 * @property int|null $status Status
 * @property string|null $comment Comment
 * @property string|null $name_insurance Название Страховой
 * @property int|null $date_osago Дата окончания ОСАГО
 * @property int|null $date_dosago Дата окончания дОсаго
 * @property int|null $date_kasko Дата окончания Каско
 * @property string|null $name_owner Владелец
 * @property int|null $fuel Fuel
 * @property int|null $filial
 */
class Cars extends \yii\db\ActiveRecord
{

    const STATUS_WORK = 1;
    const STATUS_REPAIR = 2;
    const STATUS_SOLD = 3;
    const STATUS_NO_ACTIVE = 4;
    const STATUS_SHARING = 5;

    public function getStatusLabel():array
    {
        return [
            self::STATUS_WORK => 'Работает',
            self::STATUS_REPAIR => 'На ремонте',
            self::STATUS_SOLD => 'Продан',
            self::STATUS_NO_ACTIVE => 'Не работает',
            self::STATUS_SHARING => 'Аренда',
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cars';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['year', 'status', 'date_osago', 'date_dosago', 'date_kasko', 'fuel', 'filial'], 'integer'],
            [['mark', 'number', 'vin', 'comment', 'name_insurance', 'name_owner'], 'string', 'max' => 255],
            [['status'], 'default', 'value' => self::STATUS_WORK],
            [['fuel'], 'default', 'value' => Constants::FUEL_GAS],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '#',
            'mark' => 'Марка',
            'year' => 'Год',
            'number' => 'Гос. номер',
            'VIN' => 'VIN',
            'status' => 'Статус',
            'comment' => 'Комментарий',
            'name_insurance' => 'Название страховой',
            'date_osago' => 'Осаго',
            'date_dosago' => 'Досаго',
            'date_kasko' => 'Каско',
            'name_owner' => 'Владелец',
            'fuel' => 'Топливо',
            'filial' => 'Филиал',
            'fullNameMark' => 'Автомобиль',
            'filialData' => 'Филиал',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFilialData(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Filials::class,['id' => 'filial']);
    }

    public function getRepairs(): \yii\db\ActiveQuery
    {
        return $this->hasMany(CarRepairs::class,['car_id' => 'id']);
    }

    public function getSharing(): \yii\db\ActiveQuery
    {
        return $this->hasMany(CarSharing::class, ['car_id' => 'id']);
    }

    /**
     * @return array
     */
    public function getAllFilials(): array
    {
        return (new Filials())->getAllFilials();
    }

    public function getFullNameMark(): string
    {
        return  $this->mark." ".$this->number;
    }

    public function getAllOwner(){
        return ArrayHelper::map(self::find()->distinct("name_owner")->all(), 'name_owner', 'name_owner');
    }

    public function getAllMarks()
    {
        return ArrayHelper::map(self::find()->select('mark')->distinct()->all(),'mark', 'mark' );
    }

    public function getWorkDriverAtDay($car_id, $dateWorkDay)
    {
        return DriverTabel::find()->where(['car_id' => $car_id])->andWhere(['work_date' => $dateWorkDay])->one();
    }

    public function isRepair():bool
    {
        return $this->status == self::STATUS_REPAIR;
    }

    /**
     * @param null $filial
     * @return array
     */
    public static function prepareCarsForAutocomplete($date,$filial = null): array
    {
        $carSharingArray = [];
        $carsWithSharing = CarSharing::find()
            ->select('car_id')
            ->where(['<=', 'date_start', strtotime($date)])
            ->andWhere(['>=', 'date_stop', strtotime($date)])
            ->distinct()
            ->asArray()
            ->all();
        foreach($carsWithSharing as $item)
            {
                array_push($carSharingArray, $item['car_id']);
            }

        Yii::debug("Дата ".strtotime($date), __METHOD__);
        Yii::debug($carsWithSharing, __METHOD__);

        return self::find()
            ->select(['concat(mark, " ", number) as value', 'concat(mark, " ", number) as  label','id as id'])
            ->where(['status' => self::STATUS_WORK])
            ->andFilterWhere(['filial' => $filial])
            ->andWhere(['not in', 'id', $carSharingArray])
            ->asArray()
            ->all();
    }

}
