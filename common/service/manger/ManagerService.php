<?php

namespace common\service\manger;

use backend\models\Manager;
use backend\models\ManagerForm;
use common\models\User;
use Yii;

class ManagerService
{

    public function createManager(ManagerForm $managerForm, User $user): Manager
    {
        $manager = new Manager();
        $manager->first_name = $managerForm->first_name;
        $manager->last_name = $managerForm->last_name;
        $manager->phone = $managerForm->phone;
        $manager->filial = $managerForm->filial;
        $manager->user_id = $user->id;
        $manager->save();

        return $manager;
    }

    public function searchDriverByIdUser($id)
    {
        return Manager::find()->where(['user_id' => $id])->one();
    }

    public function generateParams(ManagerForm $managerForm): array
    {
        return [
            'username' => $managerForm->username,
            'userEmail' => $managerForm->email,
            'userPassword' => $managerForm->password,
            'role' => $managerForm->role,
            'status' => $managerForm->status
        ];

    }

    public function prepareViewManagerForm(Manager $manager)
    {
        $user = User::find()->where(['id' => $manager->user_id])->one();
        $managerForm = new ManagerForm();
        $managerForm->id = $manager->id;
        $managerForm->first_name = $manager->first_name;
        $managerForm->last_name = $manager->last_name;
        $managerForm->phone = $manager->phone;
        $managerForm->filial = $manager->filial;
        $managerForm->username = $user->username;
        $managerForm->email = $user->email;
        $managerForm->status = $user->status;
        $managerForm->role = $user->role;

        return $managerForm;
    }

    public function update(int $id, ManagerForm $managerForm)
    {
        $manager = Manager::find()->where(['id'=>$id])->one();
        $user = User::find()->where(['id' => $manager->user_id])->one();
        Yii::debug(($manager->phone != $managerForm->phone), __METHOD__);

        if($manager->first_name != $managerForm->first_name) $manager->first_name = $managerForm->first_name;
        if($manager->last_name != $managerForm->last_name) $manager->last_name = $managerForm->last_name;
        if($manager->phone != $managerForm->phone) $manager->phone = $managerForm->phone;
        if($manager->filial != $managerForm->filial) $manager->filial = $managerForm->filial;

        Yii::debug($manager->phone, __METHOD__);

        $manager->save();

        if($user->username != $managerForm->username) $user->username = $managerForm->username;
        if($user->email != $managerForm->email) $user->email = $managerForm->email;
        if($user->status != $managerForm->status) $user->status = $managerForm->status;
        if($user->role != $managerForm->role) $user->role = $managerForm->role;

        $user->save();
    }

}