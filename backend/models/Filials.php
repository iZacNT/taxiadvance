<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "filials".
 *
 * @property int|null $id
 * @property string|null $name
 * @property string|null $city
 * @property string|null $phone
 * @property string|null $street
 * @property string|null $home
 * @property string|null $corpus
 * @property string|null $apartament
 * @property string|null $plan
 * @property int|null $create_at
 */
class Filials extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'filials';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'create_at'], 'integer'],
            [['name', 'city', 'phone', 'street', 'home', 'corpus', 'apartament', 'plan'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Филиал',
            'city' => 'Город',
            'phone' => 'Телефон',
            'street' => 'Улица',
            'home' => 'Дом',
            'corpus' => 'Корпус',
            'apartament' => 'Квартира',
            'plan' => 'Plan',
            'create_at' => 'Добвален',
        ];
    }

    public static function getAllFilials()
    {
        return ArrayHelper::map(self::find()->all(), 'id', 'name');
    }
}
