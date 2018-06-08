<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\db\Query;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
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
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionSitelogin()
    {
        
        $data = $_POST['LoginForm']['username'];  
        $pass = $_POST['LoginForm']['password'];  
        $Q1 = "select * from user1 where username='$data' and password='$pass'";
        $rCreat  = Yii::$app->db->createCommand($Q1)->queryAll(); 
        
        if(count($rCreat)==0){
            return $this->redirect(['index']);
        }else
        {
            return $this->redirect(['siteuser']);
        }
    }

    public function actionSiteregister()
    {
        
        $data = $_POST['LoginForm']['username'];  
        $pass = $_POST['LoginForm']['password'];  
        $Qe = "INSERT INTO user1 (username, password) VALUES ('".$data."', '".$pass."')";
        $rProj  =  Yii::$app->db->createCommand($Qe)->execute(); 
        $last_id = Yii::$app->db->getLastInsertID($rProj);
        return $this->redirect(['siteuser']);

    }
public function actionSiteuser()
    {
        
        $query = (new Query())
            -> select([
                'user1.*'
            ])
            -> from('user1');
        $countQuery = clone $query;
        $models = $query->orderBy('user1.username ASC')
                    ->all();
        $data = Json::encode($models); 
        if(isset($_GET['debug']))
        {
                header("Content-type:application/json");echo ($data);exit();
        }
            
        return $this->render('user', array(
                'data'=>$data
            ));
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

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
