<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "phones".
 *
 * @property int $id
 * @property string|null $emei Emei
 * @property string|null $mark Mark
 * @property string|null $sim_number Sim number
 * @property int|null $phone_id Phone ID
 */
class Phones extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'phones';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['phone_id'], 'required'],
            [['phone_id'], 'integer'],
            [['emei', 'mark', 'sim_number'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'emei' => 'EMEI',
            'mark' => 'Марка/Модель',
            'sim_number' => '№ SIM-карты',
            'phone_id' => 'ID',
        ];
    }

    public static function preparePhonesForAutocomplete($freePhones = []): array
    {
        return self::find()
            ->select(['concat("№", phone_id, ": ", mark) as value', 'concat("№", phone_id, ": ", mark) as  label','id as id'])
            ->where(['not in','id', $freePhones])
            ->asArray()
            ->all();
    }

    public function getFullInfo()
    {
        return "№".$this->phone_id.": ".$this->mark;
    }
}
