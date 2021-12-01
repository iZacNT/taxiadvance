<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\CarSharing;

/**
 * CarSharingSearch represents the model behind the search form of `backend\models\CarSharing`.
 */
class CarSharingSearch extends CarSharing
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'car_id', 'date_start', 'date_stop'], 'integer'],
            [['comments'], 'safe'],
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
        $query = CarSharing::find();

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
            'car_id' => $this->car_id,
            'date_start' => $this->date_start,
            'date_stop' => $this->date_stop,
        ]);

        $query->andFilterWhere(['like', 'comments', $this->comments]);

        return $dataProvider;
    }
}
