<?php

namespace backend\models;

/**
 * This is the ActiveQuery class for [[SettingsTo]].
 *
 * @see SettingsTo
 */
class SettingsToQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return SettingsTo[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return SettingsTo|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function settingsData()
    {
        return $this->where(['id' => 1])->one();
    }
}
