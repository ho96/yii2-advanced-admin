<?php

use yii\web\View;
use yii\helpers\Url;

$this->registerJs(
"
		resizeIframe();
		
		$(window).resize(function(){
			resizeIframe();
		});
		
		function resizeIframe()
		{
			$('iframe').attr('width',$('#box').width());
			$('iframe').attr('height','600');			
		}
	"
, View::POS_END, 'iframejs');

$this->registerCss("
		#content
		{
			padding:0px;
		}");	
?>

<iframe src="<?= 'lib/phpliteadmin' ?>">
	
</iframe>