<?php

namespace common\service\user;

use common\models\User;

class UserService
{
    public function create(User $user, array $params): User
    {
            $user->username = $params['username'];
            $user->email = $params['userEmail'];
            $user->status = $params['status'];
            $user->role = $params['role'];
            $user->setPassword($params['userPassword']);
            $user->generateAuthKey();
            $user->generateEmailVerificationToken();
            $user->save();
            $user->refresh();
        return $user;
    }
}