<?php

namespace common\service\yandex;

use common\service\yandex\params\ParamsSearchDriver;
use common\service\yandex\params\YandexQueryParamsInterface;
use yii\httpclient\Client;

class YandexApi
{

    private $url;
    private $clientID;
    private $apiKey;
    private $query;

    public function __construct($url, $clientID, $apiKey,YandexQueryParamsInterface $params)
    {
        $this->url = $url;
        $this->clientID = $clientID;
        $this->apiKey = $apiKey;
        $this->query = $params;

    }

    public function __destruct()
    {
        unset($this->url);
        unset($this->clientID);
        unset($this->apiKey);
        unset($this->query);
    }

    public function request(){
            $client = new Client(['requestConfig' => [
                'format' => Client::FORMAT_JSON
            ],
                'responseConfig' => [
                    'format' => Client::FORMAT_JSON
                ],]);
            $response = $client->createRequest()
                ->setMethod("POST")
                ->setUrl($this->url)
                ->setHeaders([
                    'X-Client-ID' => 'taxi/park/'.$this->clientID,
                    'X-API-Key' => $this->apiKey,
                    'Accept-Language' => 'ru-RU'
                ])
                ->setData([
                    "limit" => 500,
                    "query" => $this->query->getParams(),
                ])
                ->send();
//
//            var_dump($response->data);
//            die;
            if ($response->isOk) {
                return $response->getData();
            }

        }
}