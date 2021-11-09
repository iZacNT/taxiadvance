<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CashRegister;

/**
 * CashRegisterSearch represents the model behind the search form of `app\models\CashRegister`.
 */
class CashRegisterSearch extends CashRegister
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'type_cash', 'cash'], 'integer'],
            [['comment'], 'string'],
            [['date_time'], 'safe'],
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
        $query = CashRegister::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['date_time' => SORT_DESC]],
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
            'type_cash' => $this->type_cash,
            'cash' => $this->cash,
//            'comment' => $this->comment,
        ])
        ->andFilterWhere(['>=', 'date_time', \Yii::$app->formatter->asTimestamp($this->date_time)]);

        $query->andWhere('comment LIKE "%'.$this->comment.'%"');

        return $dataProvider;
    }
}
