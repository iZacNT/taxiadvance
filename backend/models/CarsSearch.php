<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Cars;

/**
 * CarsSearch represents the model behind the search form of `backend\models\Cars`.
 */
class CarsSearch extends Cars
{
    public $fullNameMark;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'year', 'status', 'date_osago', 'date_dosago', 'date_kasko', 'fuel', 'filial'], 'integer'],
            [['mark', 'number', 'vin', 'comment', 'name_insurance', 'name_owner', 'fullNameMark'], 'safe'],
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

        $dataProvider->setSort([
            'attributes' => [
                'vin',
                'name_owner',
                'status',
                'adress',
                'filial',
                'fullNameMark' => [
                    'asc' => ['mark' => SORT_ASC, 'number' => SORT_ASC],
                    'desc' => ['mark' => SORT_DESC, 'number' => SORT_DESC],
                    'default' => SORT_ASC
                ],

            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'mark', $this->mark])
            ->andFilterWhere(['like', 'number', $this->number])
            ->andFilterWhere(['like', 'vin', $this->vin])
            ->andFilterWhere(['like', 'comment', $this->comment])
            ->andFilterWhere(['like', 'name_insurance', $this->name_insurance])
            ->andFilterWhere(['like', 'name_owner', $this->name_owner])
            ->andFilterWhere(['like', 'filial', $this->filial])

            ->andFilterWhere(['like', 'mark', $this->fullNameMark])
            ->orFilterWhere(['like', 'number', $this->fullNameMark]);

        return $dataProvider;
    }
}
