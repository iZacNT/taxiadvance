<?php


namespace common\service\dayPlans;


use app\models\DayPlans;
use yii\db\Exception;

class DayPlansService
{

    /**
     * @throws Exception
     */
    public function createWeekEndDay(DayPlans $dayPlans, $weekendDay, $filial): void
    {
        try {
            $dayPlans->name = $dayPlans::WEEKEND_DAY;
            $dayPlans->period = $dayPlans::PERIOD_DAY;
            $dayPlans->filial = $filial;
            $dayPlans->hours_12 = $weekendDay['hour12'];
            $dayPlans->hours_16 = $weekendDay['hour16'];
            $dayPlans->save();
        } catch (\Exception $exception){
            throw new Exception($exception->getMessage());
        }

    }

    /**
     * @throws Exception
     */
    public function createWeekEndNight(DayPlans $dayPlans ,$weekenNight, $filial): void
    {
        try {

            $dayPlans->name = $dayPlans::WEEKEND_DAY;
            $dayPlans->period = $dayPlans::PERIOD_NIGHT;
            $dayPlans->filial = $filial;
            $dayPlans->hours_12 = $weekenNight['hour12'];
            $dayPlans->hours_16 = $weekenNight['hour16'];
            $dayPlans->save();

        } catch (\Exception $exception){
            throw new Exception($exception->getMessage());
        }

    }

    /**
     * @throws Exception
     */
    public function createWorkingDay(DayPlans $dayPlans ,$workingDay, $filial): void
    {
        try {

            $dayPlans->name = $dayPlans::WORKING_DAY;
            $dayPlans->period = $dayPlans::PERIOD_DAY;
            $dayPlans->filial = $filial;
            $dayPlans->hours_12 = $workingDay['hour12'];
            $dayPlans->hours_16 = $workingDay['hour16'];
            $dayPlans->save();

        } catch (\Exception $exception){
            throw new Exception($exception->getMessage());
        }

    }

    /**
     * @throws Exception
     */
    public function createWorkingNight(DayPlans $dayPlans ,$workingNight, $filial): void
    {
        try {

            $dayPlans->name = $dayPlans::WORKING_DAY;
            $dayPlans->period = $dayPlans::PERIOD_NIGHT;
            $dayPlans->filial = $filial;
            $dayPlans->hours_12 = $workingNight['hour12'];
            $dayPlans->hours_16 = $workingNight['hour16'];
            $dayPlans->save();

        } catch (\Exception $exception){
            throw new Exception($exception->getMessage());
        }

    }

    /**
     * @throws Exception
     */
    public function updateWeekEndDay($dayPlan, $weekendDay): void
    {
        try {
            $dayPlan->hours_12 = $weekendDay['hour12'];
            $dayPlan->hours_16 = $weekendDay['hour16'];
            $dayPlan->save();
        } catch (\Exception $exception){
            throw new Exception($exception->getMessage());
        }

    }

    /**
     * @throws Exception
     */
    public function updateWeekEndNight($dayPlan ,$weekenNight): void
    {
        try {
            $dayPlan->hours_12 = $weekenNight['hour12'];
            $dayPlan->hours_16 = $weekenNight['hour16'];
            $dayPlan->save();

        } catch (\Exception $exception){
            throw new Exception($exception->getMessage());
        }

    }

    /**
     * @throws Exception
     */
    public function updateWorkingDay($dayPlan ,$workingDay): void
    {
        try {
            $dayPlan->hours_12 = $workingDay['hour12'];
            $dayPlan->hours_16 = $workingDay['hour16'];
            $dayPlan->save();

        } catch (\Exception $exception){
            throw new Exception($exception->getMessage());
        }

    }

    /**
     * @throws Exception
     */
    public function updateWorkingNight($dayPlan ,$workingNight): void
    {
        try {
            $dayPlan->hours_12 = $workingNight['hour12'];
            $dayPlan->hours_16 = $workingNight['hour16'];
            $dayPlan->save();

        } catch (\Exception $exception){
            throw new Exception($exception->getMessage());
        }

    }

}