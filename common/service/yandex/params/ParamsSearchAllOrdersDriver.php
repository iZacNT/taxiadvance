<?php

namespace common\service\yandex\params;

class ParamsSearchAllOrdersDriver implements YandexQueryParamsInterface
{

    private $clientID;
    private $driver_profile_id;
    private $booked_atFrom;
    private $booked_atTo;

    public function __construct(string $clientID, $driver_profile_id , int $booked_atFrom = null, int $booked_atTo = null )
    {
        $this->clientID = $clientID;
        $this->driver_profile_id = $driver_profile_id;
        $this->booked_atFrom = date('c', $booked_atFrom);
        $this->booked_atTo = date('c', $booked_atTo);

    }

    public function getParams()
    {

        $query = [
                'park' => [
                    'driver_profile' => [
                        'id' => $this->driver_profile_id,
                    ],
                    'id' => $this->clientID,
                    'order' => [
                        'booked_at' => [
                            'from' => $this->booked_atFrom,
                            'to' => $this->booked_atTo
                            ]
                    ]
                ],
        ];

        return  $query;

    }
}