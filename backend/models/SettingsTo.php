<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "settings_to".
 *
 * @property int $id
 * @property int|null $inspection Тех. осмотр
 * @property int|null $inspection_gas Газ
 * @property int|null $inspection_grm ГРМ
 * @property int|null $inspection_gearbox Коробка передач
 * @property int|null $inspection_camber Развал/Схождение
 */
class SettingsTo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'settings_to';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['inspection', 'inspection_gas', 'inspection_grm', 'inspection_gearbox', 'inspection_camber'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'inspection' => 'Тех. осмотр',
            'inspection_gas' => 'Газ',
            'inspection_grm' => 'ГРМ',
            'inspection_gearbox' => 'Коробка передач',
            'inspection_camber' => 'Развал/Схождение',
        ];
    }

    /**
     * {@inheritdoc}
     * @return SettingsToQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SettingsToQuery(get_called_class());
    }
}
