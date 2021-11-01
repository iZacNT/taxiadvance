<?php

namespace common\service\driver;

use backend\models\Driver;
use common\models\User;
use common\service\user\UserParamsInterface;
use common\service\user\UserService;
use Yii;

class DriverParams implements UserParamsInterface
{
    private $last_name;
    private $first_name;
    private $patronymic;

    public function __construct(Driver $driver)
    {
     $this->last_name = $driver->last_name;
     $this->first_name = $driver->first_name;
     $this->patronymic = $driver->patronymic;
    }

    public function generateParams(): array
    {
        $username = \Yii::$app->formatter->asTranslit($this->last_name)."_".\Yii::$app->formatter->asTranslit($this->first_name)."_".\Yii::$app->formatter->asTranslit($this->patronymic);
        $userEmail = \Yii::$app->security->generateRandomString(10)."@someadrees.ru";
        $userPassword = '123456789';
        $role = 3;
        $status = 10;

        return ['username' => $username, 'userEmail' => $userEmail, 'userPassword' => $userPassword, 'role' => $role, 'status' => $status];
    }

    public function createDriver(Driver $driver, User $user)
    {
        $driver->shift_closing = strtotime($driver->stringShiftClosing);
        $driver->birth_date = strtotime($driver->stringBirthDay);
        $driver->user_id = $user->id;
        $driver->save();
        Yii::debug($driver->errors, __METHOD__);
    }

    public function searchDriverByIdUser($id)
    {
        return Driver::find()->where(['user_id' => $id])->one();
    }

}