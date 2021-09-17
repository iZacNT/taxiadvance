<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cash_register".
 *
 * @property int $id #
 * @property int|null $type_cash Тип
 * @property int|null $cash Cash
 * @property int|null $comment Comments
 * @property int|null $date_time Comments
 */
class CashRegister extends \yii\db\ActiveRecord
{

    const TYPE_PRIHOD = 1;
    const TYPE_RASHOD = 2;
    const TYPE_DOLG_PO_SMENE = 3;
    const TYPE_CLOSED = 4;

    public function getTypeCash()
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
            [['type_cash', 'cash', 'date_time'], 'integer'],
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
        ];
    }

    public function getSummInCashRegister()
    {
        $result = 0;
        $allRows = self::find()
            ->where(['<>','type_cash', 3])
            ->andWhere(['<>','type_cash', 4])
            ->all();
        foreach ($allRows as $row){
            if($row->type_cash == 1){
                $result += $row->cash;
            }
            if($row->type_cash == 2){
                $result -= $row->cash;
            }
        }
        return $result;
    }

    public function isCloseCashRegistry()
    {
        return $this->type_cash == self::TYPE_CLOSED;
    }
}
