<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="center">
   <h1><?= Html::encode(Yii::t('messages', 'login.login')) ?></h1>

   <p><?= Yii::t('messages', 'login.pleasefill') ?></p>
	<br/>
   <?php $form = ActiveForm::begin(['id' => 'login-form']);
        $field = $form->field($model, 'username');
        $field->template = '{label}<br/>{input}{error}';
        echo $field->textInput(['class' => 'login-item']);

        $field = $form->field($model, 'password');
        $field->template = '{label}<br/>{input}{error}';
        echo $field->passwordInput(['class' => 'login-item']);
		
        echo $form->field($model, 'rememberMe')->checkbox();
	?>
        <div class="center margin-40-0-20-0">
            <?= Html::submitButton(Yii::t('messages', 'login.login'), ['class' => 'login-item', 'name' => 'login-button']) ?>
        </div>

    <?php ActiveForm::end(); ?>
</div>
