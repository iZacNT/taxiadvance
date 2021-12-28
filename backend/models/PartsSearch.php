<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Parts;
use yii\data\ArrayDataProvider;
use yii\db\Expression;

/**
 * PartsSearch represents the model behind the search form of `backend\models\Parts`.
 */
class PartsSearch extends Parts
{
    public $sumPartsOnStock;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name_part', 'mark', 'sumPartsOnStock', 'count_in_stock'], 'safe'],
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
     * @return ArrayDataProvider
     */
    public function search($params)
    {

        $this->load($params);


        $query = Parts::find();
        // add conditions that should always apply here

        $allData = $query->all();

        if($this->name_part){
            $allData=array_filter($allData, function ($element){
                return $element->name_part == $this->name_part;
            });
        }

        if($this->mark){
            $allData=array_filter($allData, function ($element){
                return $element->mark == $this->mark;
            });
        }

        if($this->sumPartsOnStock){
            $allData=array_filter($allData, function ($element){
                return intval($element->sumPartsOnStock) > $this->sumPartsOnStock;
            });

        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $allData,
            'sort' => [ // подключаем сортировку
                'attributes' => ['name_part', 'mark', 'sumPartsOnStock'],
            ],
            'pagination' => [ //постраничная разбивка
                'pageSize' => 50, // 10 новостей на странице
            ],
        ]);


//        if (!$this->validate()) {
//            // uncomment the following line if you do not want to return any records when validation fails
//            // $query->where('0=1');
//            return $dataProvider;
//        }

//        // grid filtering conditions
//        $query->andFilterWhere([
//            'id' => $this->id,
//        ]);
//
//        $query->andFilterWhere(['like', 'name_part', $this->name_part])
//            ->andFilterWhere(['like', 'mark', $this->mark]);

        return $dataProvider;
    }
}
