<?php

namespace common\service\yandex\params;

class ParamsSearchDriver implements YandexQueryParamsInterface
{
    private $clientID;
    private $driver_license;
    private $driver_profile_id;

    public function __construct(string $clientID, string $driver_license = null, $driver_profile_id = [])
    {
        $this->clientID = $clientID;
        $this->driver_license = $driver_license;
        $this->driver_profile_id = $driver_profile_id;
    }

    public function getParams(): array
    {
        $query = [
            'park' => [
                'driver_profile' => [
                    'id' => $this->driver_profile_id,
                ],
                'id' => $this->clientID,
            ],
            'text' => $this->driver_license,
        ];

        return  $query;
    }
}