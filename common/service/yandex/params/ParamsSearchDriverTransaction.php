<?php

namespace common\service\yandex\params;

class ParamsSearchDriverTransaction implements YandexQueryParamsInterface
{
    private $clientID;
    private $driver_profile_id;
    private $event_atFrom;
    private $event_atTo;

    public function __construct(string $clientID, $driver_profile_id = null, int $event_atFrom  = null, int $event_atTo  = null)
    {
        $this->clientID = $clientID;
        $this->driver_profile_id = $driver_profile_id;
        $this->event_atFrom = $event_atFrom;
        $this->event_atTo = $event_atTo;
    }

    public function getParams(): array
    {
        $query = [
            'park' => [
                'driver_profile' => [
                    'id' => $this->driver_profile_id,
                ],
                'id' => $this->clientID,
                'transaction' => [
                    'event_at' => [
                        'from' => date("c", $this->event_atFrom),
                        'to' => date("c", $this->event_atTo)
                    ],
                ],
            ],
        ];

        return  $query;
    }
}