<?php

namespace backend\controllers;

use backend\models\Manager;
use backend\models\ManagerForm;
use backend\models\ManagerSearch;
use common\models\User;
use common\service\manger\ManagerService;
use common\service\user\UserService;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ManagerController implements the CRUD actions for Manager model.
 */
class ManagerController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Manager models.
     * @return mixed
     */
    public function actionIndex()
    {
        //$searchModel = new ManagerSearch();
        $dataProvider = new ActiveDataProvider([
            'query' => Manager::find()
        ]);

        return $this->render('index', [
           // 'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Manager model.
     * @param int $id #
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $managerForm = (new ManagerService())->prepareViewManagerForm($this->findModel($id));
        return $this->render('view', [
            'model' => $managerForm,
        ]);
    }

    /**
     * Creates a new Manager model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $managerForm = new ManagerForm();
        $managerForm->scenario = $managerForm::SCENARIO_CREATE;

        if ($this->request->isPost) {
            if ($managerForm->load($this->request->post())) {
                $userService = new UserService;
                $mangerService = new ManagerService();
                $params = $mangerService->generateParams($managerForm);
                $user = $userService->searchUserByUsername($managerForm->username);
                if (!$user){
                    $user = $userService->create(new User(), $params);
                    $user->refresh();

                    $manager = $mangerService->createManager($managerForm, $user);
                    $manager->refresh();
                }else{
                    if ($mangerService->searchDriverByIdUser($user->id)){
                        Yii::$app->session->setFlash('error', 'Водитель уже существует!');
                        return $this->redirect(['create']);
                    }else{
                        $manager = $mangerService->createManager($managerForm, $user);
                        $manager->refresh();
                        $this->redirect(['view', 'id' => $manager->id]);
                    }
                }

                return $this->redirect(['view', 'id' => $manager->id]);
            }
        }

        return $this->render('create', [
            'managerForm' => $managerForm,
        ]);
    }

    /**
     * Updates an existing Manager model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id #
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $manager = $this->findModel($id);
        $managerForm = (new ManagerService())->prepareViewManagerForm($manager);

        if ($this->request->isPost && $managerForm->load($this->request->post())) {
            Yii::debug($this->request->post(), __METHOD__);

            (new ManagerService())->update($id, $managerForm);

            return $this->redirect(['view', 'id' => $id]);
        }

        return $this->render('update', [
            'managerForm' => $managerForm,
        ]);
    }

    /**
     * Deletes an existing Manager model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id #
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $manager = $this->findModel($id);
        (User::find()->where(['id' => $manager->user_id])->one())->delete();
        $manager->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Manager model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id #
     * @return Manager the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Manager::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
