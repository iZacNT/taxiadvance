<?php

namespace backend\models;

use common\models\User;
use Yii;
use yii\base\Model;

/**
 *
 * @property int $id #
 * @property int|null $user_id User ID
 * @property string|null $first_name Name
 * @property string|null $last_name Last Name
 * @property string|null $password Password
 * @property string|null $phone Phone
 * @property int|null $filial Filial
 * @property string $username User Name
 * @property string $email Email
 * @property int $status Status
 * @property-read string $fullName
 * @property string $role Role
 */
class ManagerForm extends Model
{
    public $id;
    public $user_id;
    public $first_name;
    public $last_name;
    public $password;
    public $phone;
    public $filial;
    public $username;
    public $email;
    public $status;
    public $role;

    const SCENARIO_CREATE = 'create_post';


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['role', 'username', 'email'], 'required'],
            [['password'], 'required', 'on' => self::SCENARIO_CREATE],
            [['user_id', 'filial', 'status', 'role', 'id'], 'integer'],
            [['first_name', 'last_name', 'phone', 'username', 'password'], 'string', 'max' => 255],
            [['email'], 'email'],
            [['filial'], 'default', 'value' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => '#',
            'user_id' => '# Пользователя',
            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
            'password' => 'Пароль',
            'phone' => 'Телефон',
            'filial' => 'Филиал',
            'username' => 'Логин',
            'email' => 'Email',
            'status' => 'Статус',
            'role' => 'Роль',
        ];
    }

    public function getFullName(): string
    {
        return $this->last_name." ".$this->first_name;
    }

    public function getStatusArray(): array
    {
        return (new \common\models\User)->getStatusLabel();
    }

    public function getRoleArray(): array
    {
        $role = (new \common\models\User)->getRoleLabel();
        unset($role[User::DRIVER], $role[User::SUPER_ADMIN]);

        return $role;
    }
}
