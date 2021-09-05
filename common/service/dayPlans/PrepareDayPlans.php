<?php


namespace common\service\dayPlans;




use app\models\DayPlans;
use yii\base\BaseObject;

class PrepareDayPlans
{
    public function prepareDayPlansArr($dayPlans): array
    {
        $resultArr = [];
        if ($dayPlans){
            foreach ($dayPlans as $plan){
                if ($plan->isWeekEndDay()){
                    array_push($resultArr,[
                        'weekendDay' => [
                            'hours12' => $plan->hours_12,
                            'hours16' => $plan->hours_16,
                        ]
                    ]);
                } //if

                if ($plan->isWeekEndNight()){
                    array_push($resultArr,[
                        'weekendNight' => [
                            'hours12' => $plan->hours_12,
                            'hours16' => $plan->hours_16,
                        ]
                    ]);
                } //if

                if ($plan->isWorkingDay()){
                    array_push($resultArr,[
                        'workingDay' => [
                            'hours12' => $plan->hours_12,
                            'hours16' => $plan->hours_16,
                        ]
                    ]);
                } //if

                if ($plan->isWorkingNight()){
                    array_push($resultArr,[
                        'workingNight' => [
                            'hours12' => $plan->hours_12,
                            'hours16' => $plan->hours_16,
                        ]
                    ]);
                } //if

            } //foreach
        } //if

        return $resultArr;
    }

    /**
     * @throws \yii\db\Exception
     */
    public function create($filial, $weekendDay, $weekenNight, $workingDay, $workingNight)
    {
        $service = new DayPlansService();
        $service->createWeekEndDay(new DayPlans, $weekendDay, $filial);
        $service->createWeekEndNight(new DayPlans, $weekenNight, $filial);
        $service->createWorkingDay(new DayPlans, $workingDay, $filial);
        $service->createWorkingNight(new DayPlans, $workingNight, $filial);

        return true;
    }

    /**
     * @throws \yii\db\Exception
     */
    public function update($filial, $weekendDay, $weekenNight, $workingDay, $workingNight)
    {
        $service = new DayPlansService();
        $dayPlanWD = DayPlans::find()
            ->where(['filial' => $filial])
            ->andWhere(['name' => DayPlans::WEEKEND_DAY])
            ->andWhere(['period' => DayPlans::PERIOD_DAY])
        ->one();
        $service->updateWeekEndDay($dayPlanWD, $weekendDay);

        $dayPlanWN = DayPlans::find()
            ->where(['filial' => $filial])
            ->andWhere(['name' => DayPlans::WEEKEND_DAY])
            ->andWhere(['period' => DayPlans::PERIOD_NIGHT])
            ->one();
        $service->updateWeekEndNight($dayPlanWN, $weekenNight);

        $dayPlanWkD = DayPlans::find()
            ->where(['filial' => $filial])
            ->andWhere(['name' => DayPlans::WORKING_DAY])
            ->andWhere(['period' => DayPlans::PERIOD_DAY])
            ->one();
        $service->updateWorkingDay($dayPlanWkD, $workingDay);

        $dayPlanWkN = DayPlans::find()
            ->where(['filial' => $filial])
            ->andWhere(['name' => DayPlans::WORKING_DAY])
            ->andWhere(['period' => DayPlans::PERIOD_NIGHT])
            ->one();
        $service->updateWorkingNight($dayPlanWkN, $workingNight);

        return true;
    }
}