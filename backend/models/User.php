<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id #
 * @property string $username User Name
 * @property string $auth_key Auth Key
 * @property string $password_hash Password
 * @property string|null $password_reset_token Reset Token
 * @property string $email Email
 * @property int $status Status
 * @property int $created_at Created aT
 * @property int $updated_at Updated At
 * @property string|null $verification_token Verification token
 * @property int|null $role Role
 */
class User extends \yii\db\ActiveRecord
{

    const SUPER_ADMIN = 1;
    const MANAGER = 2;
    const DRIVER = 3;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'auth_key', 'password_hash', 'email', 'created_at', 'updated_at'], 'required'],
            [['status', 'created_at', 'updated_at', 'role'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email', 'verification_token'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
            [['role'], 'default', 'value' => self::DRIVER ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '№'),
            'username' => Yii::t('app', 'Логин'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
            'email' => Yii::t('app', 'Email'),
            'status' => Yii::t('app', 'Статус'),
            'created_at' => Yii::t('app', 'Добавлен'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'verification_token' => Yii::t('app', 'Verification Token'),
            'role' => Yii::t('app', 'Роль'),
        ];
    }

    public static function isSuperUser()
    {
        return self::SUPER_ADMIN == Yii::$app->user->identity->role;
    }

    public static function isManager()
    {
        return self::MANAGER == Yii::$app->user->identity->role;
    }

    public static function isDriver()
    {
        return self::DRIVER == Yii::$app->user->identity->role;
    }
}
