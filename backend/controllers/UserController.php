<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;
use app\components\BaseController;
use app\models\User;


class UserController extends BaseController
{
    /**
     * @inheritdoc
     */		
	public function actionUpdate()
	{
		$model = User::find()->where(['id' => 1])->one();
		
		if(isset($_POST['User']))
		{
			$model->attributes = $_POST['User'];
			//$model->attributes = $model->load(Yii::$app->request->post());
			$model->username = trim(strtolower($model->username));
			
			if($model->save())
			{
				Yii::$app->getSession()->setFlash('success',Yii::t('messages', 'common.changessaved'));
			}
		}

        $model->password = null;
		$model->repeatPassword = null;
		
		return $this->render('update',array(
			'model'=>$model,
		));
	}
}
