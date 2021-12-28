<?php

namespace backend\controllers;

use backend\models\Cars;
use backend\models\Debt;
use common\service\driver\DriverDebt;
use yii\web\Controller;
use yii\web\Response;

class DebtController extends Controller
{

    public function actionCreate($idDriver)
    {
        $debt = new Debt();

        $cars = Cars::prepareCarsForAutocomplete(\Yii::$app->user->identity->getFilialUser());

        if ($this->request->isPost) {
            if ($debt->load($this->request->post())) {

                (new DriverDebt($idDriver))->createDebt($debt);

                return $this->redirect(['driver/view', 'id' => $idDriver]);
            }
        } else {
            $debt->loadDefaultValues();
        }

        return $this->render('create', [
            'debt' => $debt,
            'idDriver' => $idDriver,
            'cars' => $cars
        ]);
    }

    /**
     * @param $id
     * @return string|Response
     */
    public function actionUpdate($id)
    {
        $debt = Debt::find()
            ->where(['id' => $id])
            ->one();
        $cars = Cars::prepareCarsForAutocomplete(\Yii::$app->user->identity->getFilialUser());

        if ($this->request->isPost && $debt->load($this->request->post())) {

            try {
                (new DriverDebt($debt->driver_id))->updateDebt($debt);
            } catch (\Exception $exception){
                throw new \DomainException($exception->getMessage());
            }

            return $this->redirect(['driver/view', 'id' => $debt->driver_id]);
        }

        foreach ($cars as $car){
            if ($car['id'] == $debt->car_id){
                $debt->stringNameCar = $car['label'];
                \Yii::debug($debt->stringNameCar, __METHOD__);
            }
        }

        return $this->render('update', [
            'debt' => $debt,
            'cars' => $cars
        ]);
    }

    /**
     * @param $id
     * @return Response
     * @throws \Throwable
     */
    public function actionDelete($id): Response
    {
        $model = Debt::find()->where(['id' => $id])->one();
        $idDriver = $model->driver_id;
        $model->delete();

        return $this->redirect(['driver/view', 'id' => $idDriver]);
    }
}