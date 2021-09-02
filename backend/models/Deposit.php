<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "deposit".
 *
 * @property int $id #
 * @property int|null $driver_id Driver ID
 * @property int|null $contributed Contributed
 * @property int|null $gave Gave
 * @property int|null $created_at
 * @property string|null $comment Comment
 *
 * @property Driver $driver
 */
class Deposit extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'deposit';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['driver_id', 'contributed', 'gave', 'created_at'], 'integer'],
            [['comment'], 'string'],
            [['driver_id'], 'exist', 'skipOnError' => true, 'targetClass' => Driver::className(), 'targetAttribute' => ['driver_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '#',
            'driver_id' => 'Водитель',
            'contributed' => 'Внесено',
            'gave' => 'Отдано',
            'created_at' => 'Дата',
            'comment' => 'Комментарий',
        ];
    }

    /**
     * Gets query for [[Driver]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDriver()
    {
        return $this->hasOne(Driver::className(), ['id' => 'driver_id']);
    }
}
