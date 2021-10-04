<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "transaction_types".
 *
 * @property int $id
 * @property string|null $type Тип
 * @property string|null $name Название
 * @property string|null $group_type Тип группы
 * @property string|null $name_group Название группы
 * @property int|null $is_enabled
 * @property int|null $is_editable
 * @property int|null $is_creatable
 * @property int|null $is_affecting_driver_balance
 * @property int|null $summarize Ссумировать
 */
class TransactionTypes extends \yii\db\ActiveRecord
{
    const UN_SUMMARIZE = 0;
    const SUMMARIZE = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transaction_types';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['summarize'],'default', 'value' => self::UN_SUMMARIZE],
            [['is_enabled', 'is_editable', 'is_creatable', 'is_affecting_driver_balance', 'summarize'], 'integer'],
            [['type', 'name', 'group_type', 'name_group'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Тип',
            'name' => 'Название',
            'group_type' => 'Тип группы',
            'name_group' => 'Название группы',
            'is_enabled' => 'Is Enabled',
            'is_editable' => 'Is Editable',
            'is_creatable' => 'Is Creatable',
            'is_affecting_driver_balance' => 'Is Affecting Driver Balance',
            'summarize' => 'Ссумировать',
        ];
    }
}
