<?php

namespace common\service\driver;

use backend\models\Debt;
use DomainException;
use Exception;
use Yii;
use yii\data\ActiveDataProvider;

class DriverDebt
{
    private $idDriver;

    public function __construct(int $idDriver)
    {
        $this->idDriver = $idDriver;
    }

    public function getAllDebt(): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => Debt::find()->filterWhere(['driver_id' => $this->idDriver]),
            'sort' => [
                'defaultOrder' => [
                    'date_reason' => SORT_DESC,
                ]
            ],
        ]);
    }

    public function getSummDebt(): int
    {
        $summContributedGave = Debt::find()
            ->select('SUM(dette) as detteSum,SUM(back) as backSum')
            ->where(['driver_id' => $this->idDriver])
            ->asArray()
            ->all();

        \Yii::debug("Сумма Долга: ".($summContributedGave[0]['detteSum'] - $summContributedGave[0]['backSum']));


        return $summContributedGave[0]['detteSum'] - $summContributedGave[0]['backSum'];
    }

    public function createDebt($debt):void
    {
        try {
            $debt->driver_id = $this->idDriver;

            if (!empty($debt->stringDateReason)) {
//                $debt->date_reason = strtotime($debt->stringDateReason);
                $debt->date_reason = Yii::$app->formatter->asTimestamp($debt->stringDateReason." ".$debt->timeDateReason);
            }
            if (!empty($debt->stringDateDtp)) {
                $debt->date_dtp = Yii::$app->formatter->asTimestamp($debt->stringDateDtp);
            }
            if (!empty($debt->stringDatePay)) {
                $debt->date_pay = Yii::$app->formatter->asTimestamp($debt->stringDatePay);
            }
            $debt->save();
        }catch (Exception $event){
            throw new DomainException($event->getMessage());
        }
    }

    public function updateDebt($debt):void
    {
        try {
            if (!empty($debt->stringDateReason)) {
                $debt->date_reason = Yii::$app->formatter->asTimestamp($debt->stringDateReason." ".$debt->timeDateReason);
            }
            if (!empty($debt->stringDateDtp)) {
                $debt->date_dtp = Yii::$app->formatter->asTimestamp($debt->stringDateDtp);
            }
            if (!empty($debt->stringDatePay)) {
                $debt->date_pay = Yii::$app->formatter->asTimestamp($debt->stringDatePay);
            }
            $debt->save();
        }catch (Exception $event){
            throw new DomainException($event->getMessage());
        }
    }

}