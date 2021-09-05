<?php


namespace common\service\calculation;


use app\models\Calculation;
use common\service\constants\Constants;
use yii\db\ActiveQuery;

class CalculationService
{
    private $filial;
    private $carMark;
    private $constants;

    public function __construct($filial,$carMark, Constants $constants)
    {
        $this->filial = $filial;
        $this->carMark = $carMark;
        $this->constants = $constants;
    }

    public function getCalculationsForMark()
        {
            $dayLessPlan = [
                'getDayLessPlan' => [
                    'DayLessPlanGas' => $this->getDayLessPlanGas(new Calculation,$this->constants),
                    'DayLessPlanGasoline' => $this->getDayLessPlanGasoline(new Calculation,$this->constants),
                ],
                'getNightLessPlan' => [
                    'NightLessPlanGas' => $this->getNightLessPlanGas(new Calculation,$this->constants),
                    'NightLessPlanGasoline' => $this->getNightLessPlanGasoline(new Calculation,$this->constants),
                ],
                'getDayBiggerPlan' => [
                    'DayBiggerPlanGas' => $this->getDayBiggerPlanGas(new Calculation,$this->constants),
                    'DayBiggerPlanGasoline' => $this->getDayBiggerPlanGasoline(new Calculation,$this->constants),
                ],
                'getNightBiggerPlan' => [
                    'NightBiggerPlanGas' => $this->getNightBiggerPlanGas(new Calculation,$this->constants),
                    'NightBiggerPlanGasoline' => $this->getNightBiggerPlanGasoline(new Calculation,$this->constants),
                ]
            ];

            return json_encode($dayLessPlan);
        }

        public function getDayLessPlanGas(Calculation $calculation,Constants $constants)
        {
            return $calculation::find()
                ->where(['car_mark' => $this->carMark])
                ->andWhere(['filial' => $this->filial])
                ->andWhere(['fuel' => $constants::FUEL_GAS])
                ->andWhere(['period' => $constants::PERIOD_DAY])
                ->andWhere(['plan' => $constants::LESS_PLAN])
                ->asArray()
                ->all();

        }

        public function getDayLessPlanGasoline(Calculation $calculation,Constants $constants)
        {
            return $calculation::find()
                ->where(['car_mark' => $this->carMark])
                ->andWhere(['filial' => $this->filial])
                ->andWhere(['fuel' => $constants::FUEL_GASOLINE])
                ->andWhere(['period' => $constants::PERIOD_DAY])
                ->andWhere(['plan' => $constants::LESS_PLAN])
                ->asArray()
                ->all();

        }

        public function getNightLessPlanGas(Calculation $calculation,Constants $constants)
        {
            return $calculation::find()
                ->where(['car_mark' => $this->carMark])
                ->andWhere(['filial' => $this->filial])
                ->andWhere(['fuel' => $constants::FUEL_GAS])
                ->andWhere(['period' => $constants::PERIOD_NIGHT])
                ->andWhere(['plan' => $constants::LESS_PLAN])
                ->asArray()
                ->all();

        }

        public function getNightLessPlanGasoline(Calculation $calculation,Constants $constants)
        {
            return $calculation::find()
                ->where(['car_mark' => $this->carMark])
                ->andWhere(['filial' => $this->filial])
                ->andWhere(['fuel' => $constants::FUEL_GASOLINE])
                ->andWhere(['period' => $constants::PERIOD_NIGHT])
                ->andWhere(['plan' => $constants::LESS_PLAN])
                ->asArray()
                ->all();

        }

        public function getDayBiggerPlanGas(Calculation $calculation,Constants $constants)
        {
            return $calculation::find()
                ->where(['car_mark' => $this->carMark])
                ->andWhere(['filial' => $this->filial])
                ->andWhere(['fuel' => $constants::FUEL_GAS])
                ->andWhere(['period' => $constants::PERIOD_DAY])
                ->andWhere(['plan' => $constants::BIGGER_PLAN])
                ->asArray()
                ->all();

        }

        public function getDayBiggerPlanGasoline(Calculation $calculation,Constants $constants)
        {
            return $calculation::find()
                ->where(['car_mark' => $this->carMark])
                ->andWhere(['filial' => $this->filial])
                ->andWhere(['fuel' => $constants::FUEL_GASOLINE])
                ->andWhere(['period' => $constants::PERIOD_DAY])
                ->andWhere(['plan' => $constants::BIGGER_PLAN])
                ->asArray()
                ->all();

        }

        public function getNightBiggerPlanGas(Calculation $calculation,Constants $constants)
        {
            return $calculation::find()
                ->where(['car_mark' => $this->carMark])
                ->andWhere(['filial' => $this->filial])
                ->andWhere(['fuel' => $constants::FUEL_GAS])
                ->andWhere(['period' => $constants::PERIOD_NIGHT])
                ->andWhere(['plan' => $constants::BIGGER_PLAN])
                ->asArray()
                ->all();

        }

        public function getNightBiggerPlanGasoline(Calculation $calculation,Constants $constants)
        {
            return $calculation::find()
                ->where(['car_mark' => $this->carMark])
                ->andWhere(['filial' => $this->filial])
                ->andWhere(['fuel' => $constants::FUEL_GASOLINE])
                ->andWhere(['period' => $constants::PERIOD_NIGHT])
                ->andWhere(['plan' => $constants::BIGGER_PLAN])
                ->asArray()
                ->all();

        }


        public function createDayLessPlan($dayLessPlan)
        {
            $this->createDayLessPlanGas(new Calculation(), $dayLessPlan['dayLessPlanDayGas']);
            $this->createDayLessPlanGasoline(new Calculation(), $dayLessPlan['dayLessPlanDayGasoline']);
        }

            public function createDayLessPlanGas(Calculation $calculation, $dayLessPlanGas)
            {
                $calculation->filial = $this->filial;
                $calculation->car_mark = $this->carMark;
                $calculation->calculation_park = $dayLessPlanGas['park'];
                $calculation->calculation_driver = $dayLessPlanGas['driver'];
                $calculation->fuel = $this->constants::FUEL_GAS;
                $calculation->period = $this->constants::PERIOD_DAY;
                $calculation->plan = $this->constants::LESS_PLAN;
                $calculation->save();
            }
            public function createDayLessPlanGasoline(Calculation $calculation, $dayLessPlanGasoline)
            {
                $calculation->filial = $this->filial;
                $calculation->car_mark = $this->carMark;
                $calculation->calculation_park = $dayLessPlanGasoline['park'];
                $calculation->calculation_driver = $dayLessPlanGasoline['driver'];
                $calculation->fuel = $this->constants::FUEL_GASOLINE;
                $calculation->period = $this->constants::PERIOD_DAY;
                $calculation->plan = $this->constants::LESS_PLAN;
                $calculation->save();
            }

        public function createNightLessPlan($nightLessPlan)
        {
            $this->createNightLessPlanGas(new Calculation(), $nightLessPlan['nightLessPlanGas']);
            $this->createNightLessPlanGasoline(new Calculation(), $nightLessPlan['nightLessPlanGasoline']);
        }
            public function createNightLessPlanGas(Calculation $calculation, $nightLessPlanGas)
            {
                $calculation->filial = $this->filial;
                $calculation->car_mark = $this->carMark;
                $calculation->calculation_park = $nightLessPlanGas['park'];
                $calculation->calculation_driver = $nightLessPlanGas['driver'];
                $calculation->fuel = $this->constants::FUEL_GAS;
                $calculation->period = $this->constants::PERIOD_NIGHT;
                $calculation->plan = $this->constants::LESS_PLAN;
                $calculation->save();
            }
            public function createNightLessPlanGasoline(Calculation $calculation, $nightLessPlanGasoline)
            {
                $calculation->filial = $this->filial;
                $calculation->car_mark = $this->carMark;
                $calculation->calculation_park = $nightLessPlanGasoline['park'];
                $calculation->calculation_driver = $nightLessPlanGasoline['driver'];
                $calculation->fuel = $this->constants::FUEL_GASOLINE;
                $calculation->period = $this->constants::PERIOD_NIGHT;
                $calculation->plan = $this->constants::LESS_PLAN;
                $calculation->save();
            }

        public function createDayBiggerPlan($dayBiggerPlan)
        {
            $this->createDayBiggerPlanGas(new Calculation(), $dayBiggerPlan['dayBiggerPlanGas']);
            $this->createDayBiggerPlanGasoline(new Calculation(), $dayBiggerPlan['dayBiggerPlanGasoline']);
        }
            public function createDayBiggerPlanGas(Calculation $calculation, $dayBiggerPlanGas)
            {
                $calculation->filial = $this->filial;
                $calculation->car_mark = $this->carMark;
                $calculation->calculation_park = $dayBiggerPlanGas['park'];
                $calculation->calculation_driver = $dayBiggerPlanGas['driver'];
                $calculation->fuel = $this->constants::FUEL_GAS;
                $calculation->period = $this->constants::PERIOD_DAY;
                $calculation->plan = $this->constants::BIGGER_PLAN;
                $calculation->save();
            }
            public function createDayBiggerPlanGasoline(Calculation $calculation, $dayBiggerPlanGasoline)
            {
                $calculation->filial = $this->filial;
                $calculation->car_mark = $this->carMark;
                $calculation->calculation_park = $dayBiggerPlanGasoline['park'];
                $calculation->calculation_driver = $dayBiggerPlanGasoline['driver'];
                $calculation->fuel = $this->constants::FUEL_GASOLINE;
                $calculation->period = $this->constants::PERIOD_DAY;
                $calculation->plan = $this->constants::BIGGER_PLAN;
                $calculation->save();
            }

        public function createNightBiggerPlan($nightBiggerPlan)
        {
            $this->createNightBiggerPlanGas(new Calculation(), $nightBiggerPlan['nightBiggerPlanGas']);
            $this->createNightBiggerPlanGasoline(new Calculation(), $nightBiggerPlan['nightBiggerPlanGasoline']);
        }
            public function createNightBiggerPlanGas(Calculation $calculation, $nightBiggerPlanGas)
            {
                $calculation->filial = $this->filial;
                $calculation->car_mark = $this->carMark;
                $calculation->calculation_park = $nightBiggerPlanGas['park'];
                $calculation->calculation_driver = $nightBiggerPlanGas['driver'];
                $calculation->fuel = $this->constants::FUEL_GAS;
                $calculation->period = $this->constants::PERIOD_NIGHT;
                $calculation->plan = $this->constants::BIGGER_PLAN;
                $calculation->save();
            }
            public function createNightBiggerPlanGasoline(Calculation $calculation, $nightBiggerPlanGasoline)
            {
                $calculation->filial = $this->filial;
                $calculation->car_mark = $this->carMark;
                $calculation->calculation_park = $nightBiggerPlanGasoline['park'];
                $calculation->calculation_driver = $nightBiggerPlanGasoline['driver'];
                $calculation->fuel = $this->constants::FUEL_GASOLINE;
                $calculation->period = $this->constants::PERIOD_NIGHT;
                $calculation->plan = $this->constants::BIGGER_PLAN;
                $calculation->save();
            }
}