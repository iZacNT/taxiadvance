<?php

namespace common\service\driver;

use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;

class PrepareOrdersService
{
    private $orders;

    public function __construct(array $orders)
    {
        $this->orders = $orders;
    }

    public function prepareOrders(): ArrayDataProvider
    {
        $provider = new ArrayDataProvider([
            'allModels' => (!empty($this->orders)) ? $this->orders : [],
            'pagination' => [
                'pageSize' => 250,
            ],
            'sort' => [
                'attributes' => [ 'booked_at'],
            ],
        ]);

        return $provider;
    }

    public function summOrders(): int
    {
        $summOrders = 0;
        foreach($this->orders as $order){
            $summOrders += $order['price'];
        }

        \Yii::debug("Сумма Заказов: ".$summOrders);

        return $summOrders;
    }

}