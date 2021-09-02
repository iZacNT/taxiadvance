<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Driver;

/**
 * DriverSearch represents the model behind the search form of `app\models\Driver`.
 */
class DriverSearch extends Driver
{

    public $fullName;
    public $adress;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'date_of_issue', 'phone'], 'integer'],
            [['user_id', 'first_name', 'status', 'last_name', 'yandex_id', 'driving_license', 'commens', 'passport', 'who_issued_it', 'city', 'street', 'hous', 'corpus', 'appartament'], 'safe'],
            [['fullName', 'adress'], 'safe'],
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
        $query = Driver::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'phone',
                'driving_license',
                'status',
                'adress',
                'fullName' => [
                    'asc' => ['first_name' => SORT_ASC, 'last_name' => SORT_ASC],
                    'desc' => ['first_name' => SORT_DESC, 'last_name' => SORT_DESC],
                    'default' => SORT_ASC
                ],
                'adress' => [
                    'asc' => [
                        'city' => SORT_ASC,
                        'street' => SORT_ASC,
                        'hous' => SORT_ASC,
                        'corpus' => SORT_ASC,
                        'appartament' => SORT_ASC,
                    ],
                    'desc' => [
                        'city' => SORT_DESC,
                        'street' => SORT_DESC,
                        'hous' => SORT_DESC,
                        'corpus' => SORT_DESC,
                        'appartament' => SORT_DESC,
                    ],
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

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'date_of_issue' => $this->date_of_issue,
        ]);

        $query->andFilterWhere(['like', 'user_id', $this->user_id])
            ->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'yandex_id', $this->yandex_id])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'driving_license', $this->driving_license])
            ->andFilterWhere(['like', 'commens', $this->commens])
            ->andFilterWhere(['like', 'passport', $this->passport])
            ->andFilterWhere(['like', 'who_issued_it', $this->who_issued_it])
            ->andFilterWhere(['like', 'status', $this->status])

            ->andFilterWhere(['like', 'first_name', $this->fullName])
            ->orFilterWhere(['like', 'last_name', $this->fullName])

            ->andFilterWhere(['like', 'city', $this->adress])
            ->orFilterWhere(['like', 'street', $this->adress])
            ->orFilterWhere(['like', 'hous', $this->adress])
            ->orFilterWhere(['like', 'corpus', $this->adress])
            ->orFilterWhere(['like', 'appartament', $this->adress]);


        return $dataProvider;
    }
}
