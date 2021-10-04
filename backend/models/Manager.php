<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "manager".
 *
 * @property int $id #
 * @property int|null $user_id User ID
 * @property string|null $first_name Name
 * @property string|null $last_name Last Name
 * @property string|null $phone Phone
 * @property int|null $filial Filial
 */
class Manager extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'manager';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'filial'], 'integer'],
            [['first_name', 'last_name', 'phone'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '#',
            'user_id' => 'User ID',
            'first_name' => 'Name',
            'last_name' => 'Last Name',
            'phone' => 'Phone',
            'filial' => 'Filial',
        ];
    }
}
