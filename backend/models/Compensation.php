<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "compensation".
 *
 * @property int $id #
 * @property int|null $summ Summ
 * @property int|null $day Day summ
 * @property int|null $night Night summ
 */
class Compensation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'compensation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['summ', 'day', 'night'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'summ' => 'Summ',
            'day' => 'Day',
            'night' => 'Night',
        ];
    }
}
