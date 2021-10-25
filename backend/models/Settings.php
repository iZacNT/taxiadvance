<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "settings".
 *
 * @property int $id #
 * @property string|null $yandex_api Yandex API
 * @property string|null $yandex_client_id Client ID
 * @property int|null $depo_min Min
 * @property int|null $depo_max Max
 * @property int|null $les_summ Less
 * @property int|null $more_summ More
 * @property int $phone_sum [int]
 */
class Settings extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['depo_min', 'depo_max', 'les_summ', 'more_summ', 'phone_sum'], 'integer'],
            [['yandex_api', 'yandex_client_id'], 'string', 'max' => 255],
            [['phone_sum'], 'default', 'value' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'yandex_api' => 'Yandex Api',
            'yandex_client_id' => 'Yandex Client ID',
            'depo_min' => 'Depo Min',
            'depo_max' => 'Depo Max',
            'les_summ' => 'Les Summ',
            'more_summ' => 'More Summ',
            'phone_sum' => 'Сумма за использование телефона'
        ];
    }
}
