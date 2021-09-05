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

    const WEEKEND_DAY = 1;
    const WORKING_DAY = 2;
    const PERIOD_DAY = 1;
    const PERIOD_NIGHT = 2;
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
            [['filial', 'period', 'hours_12', 'hours_16', 'name'], 'integer'],
            [['name'], 'in', 'range' => [self::WEEKEND_DAY, self::WORKING_DAY]],
            [['period'], 'in', 'range' => [self::PERIOD_DAY, self::PERIOD_NIGHT]]
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

    public static function daysLabel()
    {
        return [
            self::WEEKEND_DAY => "Выходной",
            self::WORKING_DAY => "Будний",

        ];
    }

    public static function periodLabel()
    {
        return [

            self::PERIOD_DAY => "День",
            self::PERIOD_NIGHT => "Ночь"
        ];
    }

    public function isWeekend(): bool
    {
        return $this->name == self::WEEKEND_DAY;
    }

    public function isWorking(): bool
    {
        return  $this->name == self::WORKING_DAY;
    }

    public function isDay(): bool
    {
        return $this->period == self::PERIOD_DAY;
    }

    public function isNight(): bool
    {
        return $this->period == self::PERIOD_NIGHT;
    }

    public function isWeekEndDay(): bool
    {
        return ($this->isWeekend() && $this->isDay());
    }

    public function isWeekEndNight(): bool
    {
        return ($this->isWeekend() && $this->isNight());
    }

    public function isWorkingDay(): bool
    {
        return ($this->isWorking() && $this->isDay());
    }

    public function isWorkingNight(): bool
    {
        return ($this->isWorking() && $this->isNight());
    }
}
