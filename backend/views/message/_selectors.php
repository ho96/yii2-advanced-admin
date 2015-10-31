<div class="center margin-bottom-20">
<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$form = ActiveForm::begin(
[
	'id'=>'messages-selector-form',
	'enableClientValidation'=>false,
	'action'=>Yii::$app->urlManager->createUrl('message/index', array('lang' => Yii::$app->language)),
]);	
	echo Html::dropDownList('language',$language, $languages,
	array(
		 'onchange'=>'
            $.post("'.Yii::$app->urlManager->createUrl('message/dynamicfiles') . '", {language:$(this).val()}, 
                function( data ) {
                	$("#file").html(data);
                });
          '
	)); 
?>
&nbsp;&nbsp;
<?php
	//empty since it will be filled by the other dropdown
	echo Html::dropDownList('file',$file, $files,array('id' => 'file'));
?>
&nbsp;&nbsp;
<?php
	echo Html::hiddenInput('lang', Yii::$app->language);
	echo Html::submitButton(Yii::t('messages', 'messages.editfile'));
	
	ActiveForm::end();
?>
</div>