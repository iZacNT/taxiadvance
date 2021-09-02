<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "settings".
 *
 * @property int $id #
 * @property string|null $yandex_api Yandex API
 * @property string|null $yandex_client_id Client ID
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
            [['yandex_api', 'yandex_client_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '#',
            'yandex_api' => 'Yandex API',
            'yandex_client_id' => 'Client ID',
        ];
    }
}
