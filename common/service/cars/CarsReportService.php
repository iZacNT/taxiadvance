<?php

namespace common\service\cars;

use backend\models\Cars;
use common\service\car_repare\CarRepareService;

class CarsReportService
{

    public function getStatusesCars(): array
    {
        $allCars = $this->getAllWorkCars();

        return [
            'allCars' => count($allCars),
            'inRepair' => $this->getInRepairCars($allCars)
        ];
    }

    /**
     * возвращаем автомобили в ремонте и их количество
     * @return array
     */
    private function getInRepairCars($allCars): array
    {
        $countInRepair = 0;
        $carsInRepair = [];
        foreach($allCars as $car){
            $carRepairService = new CarRepareService($car->id);
            if($carRepairService->hasActiveRepair()){
                $carsInRepair[] = [
                    'id' => $car->id,
                    'fullName' => $car->fullNameMark,
                ];
                $countInRepair+=1;
            }
        }
        return $carsInRepair;
    }

    /**
     * Берем все работающие автомобили
     * @return array
     */
    private function getAllWorkCars(): array
    {
        return Cars::find()->where(['status' => Cars::STATUS_WORK])->all();
    }

}