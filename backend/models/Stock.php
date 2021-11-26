<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "stock".
 *
 * @property int $id #
 * @property string|null $part_name Деталь
 * @property int|null $count Количество
 * @property int|null $type Тип
 * @property int|null $date Дата/Время
 * @property int $repair_id [int]  № Ремонта
 * @property string $comment [varchar(255)]  Комментарий
 * @property string $invoice [varchar(255)]  Накладная
 */
class Stock extends \yii\db\ActiveRecord
{

    public $typeStock = ['1' => 'Приход', '2' => 'Списание'];

    public $stringNamePart;
    public $stringNameRepair;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'stock';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['stringNamePart', 'part_name', 'type',], 'required'],
            [['date'], 'default', 'value' => time()],
            [['count', 'type', 'date', 'repair_id'], 'integer'],
            [['part_name', 'comment', 'stringNamePart', 'invoice', 'stringNameRepair'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '#',
            'part_name' => 'Деталь',
            'count' => 'Количество',
            'type' => 'Тип',
            'date' => 'Дата/Время',
            'invoice' => 'Накладная',
            'repair_id' => '№ Ремонта',
            'comment' => 'Комментарий',
            'stringNamePart' => 'Деталь',
            'stringNameRepair' => '№ ремонта',
            'partName' => 'Деталь',
        ];
    }

    public function getPartInfo(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Parts::class,['id' => 'part_name']);
    }

    public function getPartName(): string
    {
        Yii::debug($this->partInfo->name_part." ".$this->partInfo->mark,__METHOD__);
        return $this->partInfo->name_part." (".$this->partInfo->mark.")";
    }

    public function getRepairInfo()
    {
        return $this->hasOne(CarRepairs::class,['id' => 'repair_id']);
    }
}
