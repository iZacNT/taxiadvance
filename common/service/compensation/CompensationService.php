<?php

namespace common\service\compensation;


use backend\models\Compensation;

class CompensationService
{

    public function save($data):bool
    {
        foreach ($data as $key=>$value){
            $search = Compensation::find()
                ->where(['summ' => $key])
                ->one();
            if(empty($search)){
                $summ = new Compensation();
                $summ->summ = $key;
                $summ->day = $value['day'];
                $summ->night = $value['night'];
                $summ->save();
            }
        }
        return true;
    }

    public function update($data):bool
    {
        foreach ($data as $key=>$value){
            $search = Compensation::find()
                ->where(['summ' => $key])
                ->one();
            $search->day = $value['day'];
            $search->night = $value['night'];
            $search->save();
        }
        return true;
    }

    public function findData():array
    {
        $result = [];
            $compensations = Compensation::find()->asArray()->all();
            foreach ($compensations as $row){
                array_push($result,[$row['summ'] => [
                    'day' => $row['day'],
                    'night' => $row['night']
                    ]
                ]);
            }
        return $result;
    }

}