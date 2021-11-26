<?php

namespace backend\models;

use yii\helpers\ArrayHelper;

/**
 * This is the ActiveQuery class for [[Parts]].
 *
 * @see Parts
 */
class PartsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Parts[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Parts|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @return array
     */
    public function allArrayPartsForAutoComplete(): array
    {
        return $this->select(['id as id', 'CONCAT(name_part, SPACE(1),"(",mark,")") AS value', 'CONCAT(name_part, SPACE(1),"(",mark,")") AS label'])->asArray()->all();
    }
}
