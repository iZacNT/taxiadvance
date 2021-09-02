<?php

namespace backend\controllers;

use backend\models\Debt;
use common\service\driver\DriverDebt;
use yii\web\Controller;

class DebtController extends Controller
{

    public function actionCreate($idDriver)
    {
        $debt = new Debt();

        if ($this->request->isPost) {
            if ($debt->load($this->request->post())) {

                $driverDebt = (new DriverDebt($idDriver))->createDebt($debt);

                return $this->redirect(['driver/view', 'id' => $idDriver]);
            }
        } else {
            $debt->loadDefaultValues();
        }

        return $this->render('create', [
            'debt' => $debt,
            'idDriver' => $idDriver,
        ]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionUpdate($id)
    {
        $debt = Debt::find()
            ->where(['id' => $id])
            ->one();

        if ($this->request->isPost && $debt->load($this->request->post())) {

            try {
                $driverDebt = (new DriverDebt($idDriver))->updateDebt($debt);
            } catch (\Exception $exception){
                throw new \DomainException($exception->getMessage());
            }

            return $this->redirect(['driver/view', 'id' => $debt->driver_id]);
        }

        return $this->render('update', [
            'debt' => $debt,
        ]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $model = Debt::find()->where(['id' => $id])->one();
        $idDriver = $model->driver_id;
        $model->delete();

        return $this->redirect(['driver/view', 'id' => $idDriver]);
    }
}