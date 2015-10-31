<?php 
use yii\helpers\Html;

echo $this->context->renderPartial('/message/_selectors',array(
	'languages'=>$languages,
	'files'=>$files,
	'language'=>$language,
	'file'=>$file,
)); ?>
<div id="messages-content">
<table id="messages-table">
	<?php	 
	foreach($fileKeys as $index => $key):
	?>
	<tr>
		<td><?= Html::a(Yii::t('messages', 'common.edit'), array('message/edit', 'language' => $language, 'file' => $file, 'message-id' => $index, 'lang' => Yii::$app->language)) ?></td>
		<td><?= $key ?></td>
		<td><?= Html::encode(substr($fileValues[$index],0,200)) ?><?= (strlen($fileValues[$index]) > 200 ? '...' : '') ?></td>
	</tr>
	<?php
	endforeach;
	?>
</table>
</div>