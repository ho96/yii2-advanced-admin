<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\LoginForm;
use yii\filters\VerbFilter;
use app\components\BaseController;

/**
 * Site controller
 */
class SiteController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login','error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout','index','about','phpmyadmin','phpliteadmin','extplorer','phpinfo'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                   // 'logout' => ['post'],
                ],
            ],
        ];
    }

	/*public function actionPage($view = 'index')
	{
	  try {
	    return $this->render('pages/' . $view);
	  } catch (InvalidParamException $e) {
	    throw new HttpException(404);
	  }
	}*/

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionPhpmyadmin()
    {
        return $this->render('phpmyadmin');
    }

    public function actionPhpliteadmin()
    {
        return $this->render('phpliteadmin');
    }
	
    public function actionExtplorer()
    {
        return $this->render('extplorer');
    }
	
    public function actionPhpinfo()
    {
        return $this->render('phpinfo');
    }
	
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
