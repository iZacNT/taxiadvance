<?php

namespace common\service\driver;

use backend\models\DriverBilling;
use yii\data\ActiveDataProvider;

class DriverAllShiftsService
{
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function getAllShiftsDataProvider(): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => $this->getAllShifts(),
            'sort' => [
                'defaultOrder' => [
                    'date_billing' => SORT_DESC,
                ]
            ],
        ]);
    }

    public function getAllShifts()
    {
        return DriverBilling::find()->where(['driver_id' => $this->id]);
    }

}