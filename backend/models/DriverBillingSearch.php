<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\DriverBilling;

/**
 * DriverBillingSearch represents the model behind the search form of `backend\models\DriverBilling`.
 */
class DriverBillingSearch extends DriverBilling
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type_day', 'plan', 'summ_driver', 'summ_park', 'percent_driver', 'percent_park', 'billing', 'hours', 'car_phone_summ', 'car_fuel_summ', 'car_wash', 'debt_from_shift', 'depo', 'input_amount', 'period', 'fuel', 'bonus_yandex', 'date_billing', 'driver_id', 'id', 'compensations', 'car_id', 'shift_id', 'verify'], 'integer'],
            [['car_mark'], 'safe'],
            [['balance_yandex'], 'number'],
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
        $query = DriverBilling::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['date_billing' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'type_day' => $this->type_day,
            'plan' => $this->plan,
            'summ_driver' => $this->summ_driver,
            'summ_park' => $this->summ_park,
            'percent_driver' => $this->percent_driver,
            'percent_park' => $this->percent_park,
            'billing' => $this->billing,
            'hours' => $this->hours,
            'car_phone_summ' => $this->car_phone_summ,
            'car_fuel_summ' => $this->car_fuel_summ,
            'car_wash' => $this->car_wash,
            'debt_from_shift' => $this->debt_from_shift,
            'depo' => $this->depo,
            'input_amount' => $this->input_amount,
            'period' => $this->period,
            'fuel' => $this->fuel,
            'bonus_yandex' => $this->bonus_yandex,
            'balance_yandex' => $this->balance_yandex,
            'date_billing' => $this->date_billing,
            'driver_id' => $this->driver_id,
            'id' => $this->id,
            'compensations' => $this->compensations,
            'car_id' => $this->car_id,
            'shift_id' => $this->shift_id,
            'verify' => $this->verify,
        ]);

        $query->andFilterWhere(['like', 'car_mark', $this->car_mark]);

        return $dataProvider;
    }
}
