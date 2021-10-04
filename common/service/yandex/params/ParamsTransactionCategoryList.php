<?php

namespace common\service\yandex\params;

class ParamsTransactionCategoryList implements YandexQueryParamsInterface
{

    private $clientID;

    public function __construct($clientID)
    {
        $this->clientID = $clientID;
    }

    public function getParams()
    {
        return [
            'park' => [
                'id' => $this->clientID,
            ],
        ];
    }
}