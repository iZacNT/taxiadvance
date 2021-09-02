<?php

namespace common\service\driver;

use backend\models\Driver;
use common\service\user\UserParamsInterface;

class DriverParams implements UserParamsInterface
{
    private $last_name;
    private $first_name;

    public function __construct(Driver $driver)
    {
     $this->last_name = $driver->last_name;
     $this->first_name = $driver->first_name;
    }

    public function generateParams(): array
    {
        $username = \Yii::$app->formatter->asTranslit($this->last_name)."_".\Yii::$app->formatter->asTranslit($this->first_name);
        $userEmail = \Yii::$app->security->generateRandomString(10)."@someadrees.ru";
        $userPassword = '123456789';
        $role = 3;
        $status = 10;

        return ['username' => $username, 'userEmail' => $userEmail, 'userPassword' => $userPassword, 'role' => $role, 'status' => $status];
    }
}