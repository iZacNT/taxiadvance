<?php

namespace common\service\driver;

use app\models\DriverBilling;
use yii\data\ActiveDataProvider;

class DriverAllShiftsService
{
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function getAllShifts(): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => DriverBilling::find()->where(['driver_id' => $this->id]),
            'sort' => [
                'defaultOrder' => [
                    'date_billing' => SORT_DESC,
                ]
            ],
        ]);
    }

}