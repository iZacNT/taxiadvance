<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\SettingsTo;

/**
 * SettingsToSearch represents the model behind the search form of `backend\models\SettingsTo`.
 */
class SettingsToSearch extends SettingsTo
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'inspection', 'inspection_gas', 'inspection_grm', 'inspection_gearbox', 'inspection_camber'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = SettingsTo::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'inspection' => $this->inspection,
            'inspection_gas' => $this->inspection_gas,
            'inspection_grm' => $this->inspection_grm,
            'inspection_gearbox' => $this->inspection_gearbox,
            'inspection_camber' => $this->inspection_camber,
        ]);

        return $dataProvider;
    }
}
