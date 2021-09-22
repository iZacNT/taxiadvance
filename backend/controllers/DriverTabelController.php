<?php


namespace backend\controllers;


use app\models\DriverTabel;
use app\models\DriverTabelSearch;
use backend\models\Cars;
use backend\models\Driver;
use common\service\driverTabel\PrepareDriverTabel;
use Throwable;
use Yii;
use yii\db\StaleObjectException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class DriverTabelController extends Controller
{
    public function actionIndex()
    {
        $searchModel = new DriverTabelSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dateSearchFrom = (Yii::$app->formatter->asBeginDay(time()))-(24*60*60);
        Yii::debug("Дата/Время начала поиска водителей в Табеле ".$dateSearchFrom." ".Yii::$app->formatter->asDatetime($dateSearchFrom) , __METHOD__);

        $prepareService = new PrepareDriverTabel($dateSearchFrom);
        $columns = $prepareService->generateColumns();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'columns' => $columns
        ]);
    }

    public function actionCreate()
    {
        $driverTabel = new DriverTabel();
        $cars = Cars::find()
            ->select(['concat(mark, " ", number) as value', 'concat(mark, " ", number) as  label','id as id'])
            ->asArray()
            ->all();
        $drivers = Driver::find()
            ->select(['concat(last_name, " ", first_name) as value', 'concat(last_name, " ", first_name) as  label','id as id'])
            ->asArray()
            ->all();

        if ($this->request->isPost) {
            if ($driverTabel->load($this->request->post())) {
                $driverTabel->work_date = strtotime($driverTabel->work_date);
                $driverTabel->save();

                return $this->redirect(['view', 'id' => $driverTabel->id]);
            }
        } else {
            $driverTabel->loadDefaultValues();
        }

        if (\Yii::$app->request->get("workDate")){
            $driverTabel->work_date = Yii::$app->formatter->asDate(\Yii::$app->request->get("workDate"),"yyyy-MM-dd");

        }
        if (\Yii::$app->request->get("carId")){
            $driverTabel->car_id = \Yii::$app->request->get("carId");
            foreach($cars as $car){
                if ($car['id'] == \Yii::$app->request->get("carId")){
                    $driverTabel->stringNameCar = $car['label'];
                }
            }
        }

        return $this->render('create', [
            'driverTabel' => $driverTabel,
            'cars' => $cars,
            'drivers' => $drivers
        ]);
    }

    public function actionUpdate(int $id)
    {
        $driverTabel = $this->findModel($id);
        $oldDate = $driverTabel->work_date;

        $cars = Cars::find()
            ->select(['concat(mark, " ", number) as value', 'concat(mark, " ", number) as  label','id as id'])
            ->asArray()
            ->all();
        $drivers = Driver::find()
            ->select(['concat(last_name, " ", first_name) as value', 'concat(last_name, " ", first_name) as  label','id as id'])
            ->asArray()
            ->all();

        if ($this->request->isPost && $driverTabel->load($this->request->post())) {
            $formattedDate = strtotime($driverTabel->work_date);
            if ($driverTabel->isValidDay($formattedDate, $driverTabel->car_id, $oldDate)){
                $driverTabel->work_date = $formattedDate;
                $driverTabel->save();
            }else{
                Yii::$app->session->setFlash("error", 'На дату <strong>'.$driverTabel->work_date.'</strong> для Автомобиля <strong>'.$driverTabel->stringNameCar.'</strong> уже назначены водители!');
                return $this->redirect(['update', 'id' => $driverTabel->id]);
            }
            return $this->redirect(['view', 'id' => $driverTabel->id]);
        }

        $driverTabel->stringDriverDay = (empty($driverTabel->fullDayDriverName->fullName))? "" : $driverTabel->fullDayDriverName->fullName ;
        $driverTabel->stringDriverNight = (empty($driverTabel->fullNightDriverName->fullName))? "" : $driverTabel->fullNightDriverName->fullName;
        foreach($cars as $car){
            if ($car['id'] == $driverTabel->car_id){
                $driverTabel->stringNameCar = $car['label'];
            }
        }

        return $this->render('update', [
            'driverTabel' => $driverTabel,
            'cars' => $cars,
            'drivers' => $drivers
        ]);
    }

    /**
     * Finds the Driver model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return DriverTabel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): DriverTabel
    {
        if (($driverTabel = DriverTabel::findOne($id)) !== null) {
            return $driverTabel;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Deletes an existing Driver model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionDelete(int $id): Response
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'driverTabel' => $this->findModel($id),
        ]);
    }
}