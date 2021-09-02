<?php


namespace common\service\driver;


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

        return $bonus;
    }
}