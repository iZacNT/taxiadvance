<?php

namespace backend\models;

use common\models\User;
use Yii;

/**
 * This is the model class for table "manager".
 *
 * @property int $id #
 * @property int|null $user_id User ID
 * @property string|null $first_name Name
 * @property string|null $last_name Last Name
 * @property string|null $phone Phone
 * @property int|null $filial
 */
class Manager extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'manager';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'filial'], 'integer'],
            [['first_name', 'last_name', 'phone'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '#',
            'user_id' => 'User ID',
            'first_name' => 'Name',
            'last_name' => 'Last Name',
            'phone' => 'Phone',
            'filial' => 'Filial',
        ];
    }

    public function getUser(): \yii\db\ActiveQuery
    {
        return $this->hasOne(User::class,['id' => 'user_id']);
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
