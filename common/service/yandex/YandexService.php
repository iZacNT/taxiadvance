<?php

namespace common\service\yandex;

use backend\models\Driver;
use backend\models\Settings;
use Cassandra\Set;
use common\service\yandex\params\ParamsSearchAllOrdersDriver;
use common\service\yandex\params\ParamsSearchDriver;
use common\service\yandex\params\ParamsSearchDriverTransaction;
use phpDocumentor\Reflection\Types\Mixed_;

class YandexService
{

    private $driver;
    public $settings;

    public function __construct(Driver $driver, Settings $settings)
    {
        $this->driver = $driver;
        $this->settings = $settings;
    }

    public function getAllOrders(){
        $params = new ParamsSearchAllOrdersDriver(
            $this->settings->yandex_client_id,
            $this->driver->yandex_id,
            $this->driver->shift_closing,
            time()
        );

        return (new YandexApi(
            'https://fleet-api.taxi.yandex.net/v1/parks/orders/list',
            $this->settings->yandex_client_id,
            $this->settings->yandex_api,
            $params
        ))->request();
    }

    public function getBalanceFromYandex(): float
        {
            $paramsBalance = new ParamsSearchDriver(
                $this->settings->yandex_client_id,
                null ,
                [ $this->driver->yandex_id ]
            );
            $balanceDriverYandex = (new YandexApi(
                'https://fleet-api.taxi.yandex.net/v1/parks/driver-profiles/list',
                $this->settings->yandex_client_id,
                $this->settings->yandex_api,
                $paramsBalance
            ))->request();
            $result = 0;
            if ($balanceDriverYandex){
                $result = $balanceDriverYandex['driver_profiles'][0]["accounts"][0]["balance"];
            }

            return $result;
        }


    public function getDriverTransaction()
    {
        $paramsTransaction = new ParamsSearchDriverTransaction(
            $this->settings->yandex_client_id,
            $this->driver->yandex_id,
            $this->driver->shift_closing,
            time()
        );

        $transactionDriverYandex = (new YandexApi(
            'https://fleet-api.taxi.yandex.net/v2/parks/driver-profiles/transactions/list',
            $this->settings->yandex_client_id,
            $this->settings->yandex_api,
            $paramsTransaction
        ))->request();

        $result = 0;
        if (!empty($transactionDriverYandex)){
            $result = $transactionDriverYandex;
        }

        return $result;
    }
}