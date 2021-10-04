<?php


namespace common\service\driver;


use backend\models\TransactionTypes;

class PrepareTransactionService
{

    private $transactions;

    public function __construct(array $transactions)
    {
        $this->transactions = $transactions;
    }

    public function getBonusDriver(): int
    {
        $bonus = 0;
        foreach ($this->transactions as $transaction){
            if ($transaction['category_id'] == 'bonus'){
                $bonus += $transaction['amount'];
            }
        }
        \Yii::debug("Бонус: ".$bonus, __METHOD__);
        return $bonus;
    }

    public function getAmountTransactionType(): array
    {
        $types = $this->getNeedType();
        \Yii::debug(serialize($types));
        $arrSumType = [];
        foreach ($types as $type){
            array_push($arrSumType, ['name' => $type->type, 'amount' => $this->getSummationByType($type->type)]);
        }
        \Yii::debug($arrSumType,__METHOD__);

        return $arrSumType;
    }

    public function getNeedType(): array
    {
        return TransactionTypes::find()->where(['summarize' => TransactionTypes::SUMMARIZE])->all();
    }

    public function getSummationByType($type): int
    {
        $amount = 0;
        foreach ($this->transactions as $transaction){
            if ($transaction['category_id'] == $type){
                $amount += $transaction['amount'];
            }
        }
        \Yii::debug($type." = ".$amount);

        return $amount;
    }

    public function getSumOfTransactionByType(): int
    {
        $amount = 0;
        foreach($this->getAmountTransactionType() as $arr){
            $amount += $arr['amount'];
        }
        \Yii::debug("Сумма всех типов транзакций: ".$amount, __METHOD__);
        return $amount;
    }
}