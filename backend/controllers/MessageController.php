<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\components\BaseController;
use app\models\Message;
use yii\helpers\Html;

class MessageController extends BaseController
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
                        'actions' => ['index','edit','dynamicfiles','save'],
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
	
	public function actionIndex()
	{
		$languages = Message::getLanguages();
		
		// Set language
		$language = '';
		if(isset($_POST['language']))
			$language = $_POST['language'];
		else
			$language = reset($languages);	
		
		$files = Message::getFiles($language);
		
		// Set file
		$file = '';
		if(isset($_POST['file']))
			$file = $_POST['file'];
		else
			$file = reset($files);

		$messages = Message::findAll($language, $file);
		$fileKeys = $messages[0];
		$fileValues = $messages[1];
		
		return $this->render('index', array('languages' => $languages, 'files' => $files, 'language' => $language, 'file' => $file, 'fileKeys' => $fileKeys, 'fileValues' => $fileValues));
		
	}

	public function actionSave()
	{
		$languages = Message::getLanguages();
		$model = new Message;
		
		$model->language = $_POST['language'];
		$model->file = $_POST['file'];
		$model->messageId = $_POST['message-id'];
		$model->message = $_POST['message-content'];
		$model->save();		
		$files = Message::getFiles($_POST['language']);
		
		Yii::$app->getSession()->setFlash('success',Yii::t('messages', 'common.changessaved'));

		return $this->render('edit', array('languages' => $languages, 'files' => $files, 'language' => $model->language, 'file' => $model->file, 'message' => $model->message, 'messageId' => $model->messageId));		
	}
	
	public function actionEdit()
	{
		$languages = Message::getLanguages();
		$model = new Message;
			
		$model->language = $_GET['language'];
		$model->file = $_GET['file'];
		$model->messageId = $_GET['message-id'];
		$model->message = Message::find($_GET['language'], $_GET['file'], $_GET['message-id']);
		$files = Message::getFiles($_GET['language']);

		return $this->render('edit', array('languages' => $languages, 'files' => $files, 'language' => $model->language, 'file' => $model->file, 'message' => $model->message, 'messageId' => $model->messageId));
	}
	

	public function actionDynamicfiles()
	{
		$path = Yii::getAlias('@frontend') . '/messages/' . $_POST['language'];
		$results = scandir($path);
		foreach ($results as $key => $value) {
		    if ($value === '.' or $value === '..')
				unset($results[$key]);
		}
	    foreach($results as $value=>$name)
	    {
	    	echo "<option value='".$name."'>" . $name . "</option>";
	    }
	}
}