<?php

namespace app\models;

use backend\models\Cars;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * DriverTabelSearch represents the model behind the search form of `app\models\DriverTabel`.
 */
class DriverTabelSearch extends Cars
{

    public $fullNameMark;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fullNameMark'], 'safe'],
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
        $query = Cars::find();

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
        $query->andFilterWhere(
            ['like', 'mark', $this->fullNameMark]
            )
        ->orFilterWhere(['like', 'number', $this->fullNameMark]);

        return $dataProvider;
    }
}
