<?php

namespace backend\controllers;

use backend\models\Deposit;
use common\service\driver\DriverDeposit;
use yii\web\Controller;

class DepositController extends Controller
{

    /**
     * @param $idDriver
     * @return string|\yii\web\Response
     */
    public function actionCreate($idDriver)
    {
        $deposit = new Deposit();

        if ($this->request->isPost) {
            if ($deposit->load($this->request->post()) && $deposit->validate()) {

                $driverDeposit = (new DriverDeposit($idDriver))->createDeposit($deposit);

                return $this->redirect(['driver/view', 'id' => $idDriver]);
            }
        } else {
            $deposit->loadDefaultValues();
        }

        return $this->render('create', [
            'deposit' => $deposit,
            'idDriver' => $idDriver,
        ]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionUpdate($id)
    {
        $deposit = Deposit::find()
            ->where(['id' => $id])
            ->one();

        if ($this->request->isPost && $deposit->load($this->request->post()) && $deposit->save()) {
            return $this->redirect(['driver/view', 'id' => $deposit->driver_id]);
        }

        return $this->render('update', [
            'deposit' => $deposit,
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
        $model = Deposit::find()->where(['id' => $id])->one();
        $idDriver = $model->driver_id;
        $model->delete();

        return $this->redirect(['driver/view', 'id' => $idDriver]);
    }

}