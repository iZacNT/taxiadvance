<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "day_plans".
 *
 * @property int $id
 * @property string|null $name Name Plan
 * @property int|null $filial Filial
 * @property int|null $period Day or Night
 * @property int|null $hours_12 Plan 12 hours
 * @property int|null $hours_16 Plan 16 Hours
 */
class DayPlans extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'day_plans';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['filial', 'period', 'hours_12', 'hours_16'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '#',
            'name' => 'Наименование плана',
            'filial' => 'Филиал',
            'period' => 'Периол',
            'hours_12' => 'План 12 часов',
            'hours_16' => 'План 16 часов',
        ];
    }
}
