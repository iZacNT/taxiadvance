<?php

namespace common\service\driver;

use backend\models\Deposit;
use yii\data\ActiveDataProvider;

class DriverDeposit
{
    private $idDriver;

    public function __construct(int $idDriver)
    {
        $this->idDriver = $idDriver;
    }

    public function getAllDeposits(): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => Deposit::find()->filterWhere(['driver_id' => $this->idDriver]),
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ],
        ]);
    }

    public function createDeposit(Deposit $deposit):void
    {
        try {
            $deposit->driver_id = $this->idDriver;
            $deposit->save();

        }catch (\Exception $event){
            throw new \DomainException($event->getMessage());
        }
    }

    public function getSummDeposit(): int
    {
        $summContributedGave = Deposit::find()
            ->select('SUM(contributed) as contributedSum,SUM(gave) as gaveSum')
            ->where(['driver_id' => $this->idDriver])
            ->asArray()
            ->all();

        $result = $summContributedGave[0]['contributedSum'] - $summContributedGave[0]['gaveSum'];

        \Yii::debug("Сумма депозита: ".$result);

        return $result;
    }
}