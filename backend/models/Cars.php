<?php

namespace backend\models;

use app\models\DriverTabel;
use backend\models\Filials;
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
            'date_kaslo' => 'Каско',
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

    /**
     * @return array
     */
    public function getAllFilials(): array
    {
        return (new Filials())->getAllFilials();
    }

    public function getFullNameMark()
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
}
