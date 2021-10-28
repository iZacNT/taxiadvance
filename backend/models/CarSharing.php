<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "car_sharing".
 *
 * @property int $id
 * @property int|null $car_id
 * @property int|null $date_start
 * @property int|null $date_stop
 * @property string|null $comments
 */
class CarSharing extends \yii\db\ActiveRecord
{

    public $stringDateStart;
    public $stringDateStop;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'car_sharing';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['car_id', 'date_start', 'date_stop'], 'integer'],
            [['stringDateStart', 'stringDateStop'], 'string'],
            [['comments'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'car_id' => 'Авто',
            'date_start' => 'Дата начала',
            'date_stop' => 'Дата окончания',
            'comments' => 'Комментарии',
            'stringDateStart' => 'Дата начала',
            'stringDateStop' => 'Дата окончания',
        ];
    }

    public function getCarSharing($car_id):ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => self::find()->where(['car_id' => $car_id])
        ]);
    }
}
