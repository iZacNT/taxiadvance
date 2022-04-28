<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "messages_from_site".
 *
 * @property int $id ID
 * @property string $username Клиент
 * @property string $phone Телефон
 * @property string $message Сообщение
 */
class MessagesFromSite extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'messages_from_site';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'phone', 'message'], 'required'],
            [['message'], 'string'],
            [['username', 'phone'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Клиент',
            'phone' => 'Телефон',
            'message' => 'Сообщение',
        ];
    }
}
