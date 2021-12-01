<?php

namespace backend\models;

use backend\models\Filials;
use Yii;

/**
 * This is the model class for table "calculation".
 *
 * @property int|null $id #
 * @property string|null $name_calculation Name Calculation
 * @property int|null $filial Filial
 * @property string|null $car_mark Mark
 * @property int|null $calculation_park Park %
 * @property int|null $calculation_driver Driver %
 * @property int|null $fuel Fuel
 * @property int|null $period Period
 * @property int|null $plan Plan
 */
class Calculation extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'calculation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'filial', 'calculation_park', 'calculation_driver', 'fuel', 'period', 'plan'], 'integer'],
            [['name_calculation', 'car_mark'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '#',
            'name_calculation' => 'Name Calculation',
            'filial' => 'Filial',
            'car_mark' => 'Mark',
            'calculation_park' => 'Park %',
            'calculation_driver' => 'Driver %',
            'fuel' => 'Fuel',
            'period' => 'Period',
            'plan' => 'Plan',
        ];
    }

}
