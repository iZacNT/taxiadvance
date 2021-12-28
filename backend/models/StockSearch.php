<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Stock;

/**
 * StockSearch represents the model behind the search form of `backend\models\Stock`.
 */
class StockSearch extends Stock
{
    public $partName;
    public $dateString;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'count', 'type', 'date'], 'integer'],
            [['part_name', 'partName','invoice',
                'repair_id', 'dateString'], 'safe'],
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
        $query = Stock::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'defaultOrder' => ['date'=>SORT_DESC],
            'attributes' => [
                'id',
                'count',
                'type',
                'date',
                'invoice',
                'repair_id',
                'partName' => [
                    'asc' => ['name_part' => SORT_ASC, 'mark' => SORT_ASC],
                    'desc' => ['name_part' => SORT_DESC, 'mark' => SORT_DESC],
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            $query->joinWith(['partInfo']);
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'count' => $this->count,
            'type' => $this->type,
            'invoice' => $this->invoice,
            'repair_id' => $this->repair_id,
        ]);

        $query->andFilterWhere(['like', 'part_name', $this->part_name]);
        $query->andFilterWhere(['>=', 'date',strtotime($this->dateString)]);
        $query->andFilterWhere(['<', 'date',(!empty($this->dateString))? strtotime($this->dateString)+(24*60*60): null]);

        $query->joinWith(['partInfo' => function ($q) {
            $q->where('parts.name_part LIKE "%' .$this->partName. '%" OR parts.mark LIKE "%'.$this->partName.'%"');
        }]);

        return $dataProvider;
    }
}
