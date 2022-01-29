<?php

namespace backend\controllers;

use backend\models\CashRegister;
use common\models\User;
use common\models\LoginForm;
use common\service\cars\CarsReportService;
use common\service\cash_registry\CashRegistryService;
use common\service\driverTabel\StatistycDriverTabel;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [

            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function beforeAction($action)
    {
        if ($this->action->id == 'index'){
            if(User::isManager() || User::isOperator()){
                $this->redirect('/admin/site/manager');
            }
            if(User::isManager() || User::isMechanic()){
                $this->redirect('/admin/site/mechanic');
            }
        }
        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }

    /**
     * Displays homepage.
     *
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex()
    {
        $carsReportService = new CarsReportService();
        $statisticCasheRegistry = new CashRegistryService();
        $statisticDriverTabel = new StatistycDriverTabel();
        $statistic =  $statisticDriverTabel->generateStatisticByDayForDashboard(Yii::$app->formatter->asTimestamp(date('Y-m-d')));
        $ddd = $statisticDriverTabel->getStatisticCurrentMonth();
        Yii::debug($ddd);
        $widGetData = [
            'donutChatAllCars' => $carsReportService->getStatusesCars(),
            'statistic' => $statistic,
            'cashRegistry' => $statisticCasheRegistry->getStatisticCashRegistry(),
            'statisticCashByDay' => $ddd
        ];

        return $this->render('index',[
            'widGetData' => $widGetData,
        ]);
    }

    public function actionManager()
    {
        return $this->render('manager');
    }

    public function actionMechanic()
    {
        return $this->render('mechanic');
    }

    /**
     * Login action.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'main-login';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

}
