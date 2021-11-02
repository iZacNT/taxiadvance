<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\DebtFines;

/**
 * DebtFinesSearch represents the model behind the search form of `backend\models\DebtFines`.
 */
class DebtFinesSearch extends DebtFines
{
    public $carFullName;
    public $driverFullName;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'driver_id', 'dette', 'back', 'reason', 'car_id', 'date_reason', 'date_dtp', 'payable', 'date_pay'], 'integer'],
            [['comment', 'regulation', 'geo_dtp', 'carFullName', 'driverFullName'], 'safe'],
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
        $query = DebtFines::find()->where(['reason' => 1]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder'=>['id'=>SORT_DESC]]
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'date_reason',
                'regulation',
                'dette',
                'back',
                'comment',
                'driverFullName' => [
                    'asc' => ['first_name' => SORT_ASC, 'last_name' => SORT_ASC, 'patronymic' => SORT_ASC],
                    'desc' => ['first_name' => SORT_DESC, 'last_name' => SORT_DESC, 'patronymic' => SORT_DESC],
                    'default' => SORT_ASC
                ],
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
            $query->joinWith(['driverInfo']);
            $query->joinWith(['carInfo']);
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'driver_id' => $this->driver_id,
            'dette' => $this->dette,
            'back' => $this->back,
            'reason' => $this->reason,
            'car_id' => $this->car_id,
            'date_reason' => $this->date_reason,
            'date_dtp' => $this->date_dtp,
            'payable' => $this->payable,
            'date_pay' => $this->date_pay,
        ]);

        $query->andFilterWhere(['like', 'comment', $this->comment])
            ->andFilterWhere(['like', 'regulation', $this->regulation])
            ->andFilterWhere(['like', 'geo_dtp', $this->geo_dtp]);

        $query->joinWith(['carInfo' => function ($q) {
            $q->where('cars.mark LIKE "%' .$this->carFullName. '%" OR cars.number LIKE "%'.$this->carFullName.'%"');
        }]);

        $query->joinWith(['driverInfo' => function ($q) {
            $q->where('driver.first_name LIKE "%' .$this->driverFullName. '%" OR driver.last_name LIKE "%'.$this->driverFullName.'%" OR driver.patronymic LIKE "%'.$this->driverFullName.'%"');
        }]);

        return $dataProvider;
    }
}
