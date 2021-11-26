<?php

namespace backend\models;

use backend\models\Cars;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

/**
 * CarsSearch represents the model behind the search form of `backend\models\Cars`.
 */
class CarsSearch extends Cars
{
    public $fullNameMark;
    public $inspection_to;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'year', 'status', 'date_osago', 'date_dosago', 'date_kasko', 'fuel', 'filial', 'mileage'], 'integer'],
            [['mark', 'number', 'vin', 'comment', 'name_insurance', 'name_owner', 'fullNameMark', 'inspection_to', 'inspection_grm_to',
                'inspection_gas_to', 'inspection_gearbox_to', 'inspection_camber_to'], 'safe'],
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
        $settingsTo = SettingsTo::find()->settingsData();
        $query = Cars::find();
        $query->addSelect([
            '*',
            new Expression('((inspection+'.$settingsTo->inspection.')-mileage) as inspection_to'),
            new Expression('((inspection_grm+'.$settingsTo->inspection_grm.')-mileage) as inspection_grm_to'),
            new Expression('((inspection_gas+'.$settingsTo->inspection_gas.')-mileage) as inspection_gas_to'),
            new Expression('((inspection_gearbox+'.$settingsTo->inspection_gearbox.')-mileage) as inspection_gearbox_to'),
            new Expression('((inspection_camber+'.$settingsTo->inspection_camber.')-mileage) as inspection_camber_to'),
        ]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'inspection_to',
                'inspection_grm_to',
                'inspection_gas_to',
                'inspection_gearbox_to',
                'inspection_camber_to',
                'mileage',
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
            ->andFilterWhere(['>=', 'mileage', $this->mileage])
            ->andFilterHaving(['<=', 'inspection_to', $this->inspection_to])
            ->andFilterHaving(['<=', 'inspection_grm_to', $this->inspection_grm_to])
            ->andFilterHaving(['<=', 'inspection_gas_to', $this->inspection_gas_to])
            ->andFilterHaving(['<=', 'inspection_gearbox_to', $this->inspection_gearbox_to])
            ->andFilterHaving(['<=', 'inspection_camber_to', $this->inspection_camber_to])

            ->andFilterWhere(['like', 'mark', $this->fullNameMark])
            ->orFilterWhere(['like', 'number', $this->fullNameMark]);

        return $dataProvider;
    }
}
