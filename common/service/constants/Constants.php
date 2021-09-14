<?php


namespace common\service\constants;


class Constants
{
    const WEEKEND_DAY = 1;
    const WORKING_DAY = 2;
    const PERIOD_DAY = 1;
    const PERIOD_NIGHT = 2;
    const FUEL_GAS = 1;
    const FUEL_GASOLINE = 0;
    const LESS_PLAN = 1;
    const BIGGER_PLAN = 2;


    public static function getDayProperty()
    {
        return [
            self::WEEKEND_DAY => 'Выходной',
            self::WORKING_DAY => 'Рабочий',

        ];
    }

    public static function getPeriod()
    {
        return [
            self::PERIOD_DAY => 'День',
            self::PERIOD_NIGHT => 'Ночь',
        ];
    }

    public static function getFuel()
    {
        return[
            self::FUEL_GAS => 'Газ',
            self::FUEL_GASOLINE => 'Бензин',
        ];
    }

    public static function getTypePlan()
    {
        return[
            self::LESS_PLAN => 'Меньше плана',
            self::BIGGER_PLAN => 'Больше плана'
        ];
    }

    public static function getTypeLabel($type, $default = null)
    {
        $types = static::getDayProperty();
        return isset($types[$type]) ? $types[$type] : $default;
    }

}