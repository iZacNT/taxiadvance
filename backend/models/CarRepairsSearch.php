<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\CarRepairs;

/**
 * CarRepairsSearch represents the model behind the search form of `backend\models\CarRepairs`.
 */
class CarRepairsSearch extends CarRepairs
{
    public $carFullName;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'car_id', 'date_open_repair', 'date_close_repare', 'status'], 'integer'],
            [['carFullName'], 'safe'],
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
        $query = CarRepairs::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['date_open_repair' => SORT_DESC]],
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'id',
                'car_id',
                'date_open_repair',
                'date_close_repare',
                'status',
                'carFullName' => [
                    'asc' => ['mark' => SORT_ASC, 'number' => SORT_ASC],
                    'desc' => ['mark' => SORT_DESC, 'number' => SORT_DESC],
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            $query->joinWith(['car']);
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'car_id' => $this->car_id,
            'date_open_repair' => $this->date_open_repair,
            'date_close_repare' => $this->date_close_repare,
            'status' => $this->status,
        ]);
        $query->joinWith(['car' => function ($q) {
            $q->where('cars.mark LIKE "%' .$this->carFullName. '%" OR cars.number LIKE "%'.$this->carFullName.'%"');
        }]);

        return $dataProvider;
    }
}
