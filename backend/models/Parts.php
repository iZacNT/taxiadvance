<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "parts".
 *
 * @property int $id
 * @property string|null $name_part Наименование детали
 * @property string|null $mark Марка Авто
 */
class Parts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'parts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name_part','mark'], 'required'],
            [['name_part', 'mark'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name_part' => 'Наименование детали',
            'mark' => 'Марка Авто',
        ];
    }

    /**
     * {@inheritdoc}
     * @return PartsQuery the active query used by this AR class.
     */
    public static function find(): PartsQuery
    {
        return new PartsQuery(get_called_class());
    }

}
