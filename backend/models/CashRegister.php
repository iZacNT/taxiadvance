<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "cash_register".
 *
 * @property int $id #
 * @property int|null $type_cash Тип
 * @property int|null $cash Cash
 * @property int|null $comment Comments
 * @property int|null $date_time Comments
 * @property int $in_calc [int]  Расчитывать
 */
class CashRegister extends \yii\db\ActiveRecord
{

    const TYPE_PRIHOD = 1;
    const TYPE_RASHOD = 2;
    const TYPE_DOLG_PO_SMENE = 3;
    const TYPE_CLOSED = 4;

    public static function getTypeCash()
    {
        return [
            self::TYPE_PRIHOD => 'Приход',
            self::TYPE_RASHOD => 'Расход',
            self::TYPE_DOLG_PO_SMENE => 'Долг по смене',
            self::TYPE_CLOSED => 'Закрытие смены'
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cash_register';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type_cash', 'cash'], 'required'],
            [['date_time'], 'default', 'value' => time()],
            [['in_calc'], 'default', 'value' => 1],
            [['type_cash', 'cash', 'date_time', 'in_calc'], 'integer'],
            [['comment'], 'string']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type_cash' => 'Тип',
            'cash' => 'Сумма',
            'comment' => 'Комментарий',
            'date_time' => 'Дата/Время',
            'in_calc' => 'В расчет',
        ];
    }

    public function getSummInCashRegister()
    {
        $result = 0;
        $allRows = self::find()
            ->where(['<>','type_cash', 3])
            ->andWhere(['<>','type_cash', 4])
            ->andWhere(['like', 'in_calc', 1])
            ->all();
        foreach ($allRows as $row){
            if($row->type_cash == self::TYPE_PRIHOD){
                $result += $row->cash;
            }
            if($row->type_cash == self::TYPE_RASHOD){
                $result -= $row->cash;
            }
        }
        return $result;
    }

    public function getSummInCashRegisterWithDolg()
    {
        $result = 0;
        $allRows = self::find()
            ->where(['<>','type_cash', 4])
            ->andWhere(['like', 'in_calc', 1])
            ->all();
        foreach ($allRows as $row){
            if($row->type_cash == self::TYPE_PRIHOD){
                $result += $row->cash;
            }
            if($row->type_cash == self::TYPE_RASHOD){
                $result -= $row->cash;
            }
            if($row->type_cash == self::TYPE_DOLG_PO_SMENE){
                $result -= $row->cash;
            }
        }
        Yii::debug("Сумма: ".$result);
        return $result;
    }

    public function isCloseCashRegistry(): bool
    {
        return $this->type_cash == self::TYPE_CLOSED;
    }

    public function getLastCloseCashRegistryData(): array
    {
        return self::find()
            ->where(['type_cash' => self::TYPE_CLOSED])
            ->limit(4)
            ->orderBy(['date_time' => SORT_DESC])
            ->all();
    }
}
