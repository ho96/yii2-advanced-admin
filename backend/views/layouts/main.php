<?php
$lang = Yii::$app->language;

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use host33\multilevelhorizontalmenu\MultilevelHorizontalMenu;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Yii::$app->name ?> Administration</title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
	<div id="responsive-menu">
		<span id="insertHere"></span>
	</div>
	<div id="box">
		<header>
			<?= Html::img('@web/images/user.gif', array('alt' => 'user','id' => 'title-image')) ?>
			<?= Html::a('<span id="title"><b>' . Yii::$app->name . '</b><br/>' . Yii::t('messages', 'layout.administration') . '</span>',array('/site/login', 'lang' => $lang)) ?>
			<?php if(!Yii::$app->user->isGuest): ?>
				<div id="logout"><?= Html::a(Html::img('@web/images/logout.png', array('alt' => 'logout', 'title' => Yii::t('messages', 'layout.logout'))), array('site/logout', 'lang' => $lang)); ?></div>
			<?php endif; ?>
			<div id="home"><?= Html::a(Html::img('@web/images/home.png', array('alt' => 'home', 'title' => Yii::t('messages', 'layout.gobackmainsite'))), Yii::$app->urlManagerFrontEnd->baseUrl); ?></div>
			<div id="flags"><?php printFlags($lang, $this->context->paramKeys, $this->context->paramValues, $this->context->languages); ?></div>
			<?php if(!Yii::$app->user->isGuest): ?>
				<div id="welcome"><?= Yii::t('messages', 'layout.welcome') . ' ' .  Yii::$app->user->identity->username ?>!</div>
			<?php endif; ?>
			<div class="clear"></div>
		</header>
		<nav>
		<?php
		if(!Yii::$app->user->isGuest):
		echo MultilevelHorizontalMenu::widget(
		array(
       'menu'=>array(
              array('url'=>array(
                           'route'=>'site/index','params'=>array('lang'=>$lang)),
                           'label'=>Yii::t('messages', 'layout.home')),
              array('url'=>array(
                           'route'=>'user/update','params'=>array('lang'=>$lang)),
                           'label'=>Yii::t('messages', 'layout.profile')),
              array('url'=>array(
                           'route'=>'message/index','params'=>array('lang'=>$lang)),
                           'label'=>Yii::t('messages', 'layout.messages')),
              array('url'=>array(
                           'route'=>'site/phpmyadmin','params'=>array('lang'=>$lang)),
                           'label'=>Yii::t('messages', 'layout.phpmyadmin')),                           
              array('url'=>array(
                           'route'=>'site/phpliteadmin','params'=>array('lang'=>$lang)),
                           'label'=>Yii::t('messages', 'layout.phpliteadmin')),
              array('url'=>array(
                           'route'=>'site/extplorer','params'=>array('lang'=>$lang)),
                           'label'=>Yii::t('messages', 'layout.extplorer')),
              array('url'=>array(
                           'route'=>'site/phpinfo','params'=>array('lang'=>$lang)),
                           'label'=>Yii::t('messages', 'layout.phpinfo')),
              array('url'=>array(
                           'route'=>'site/about','params'=>array('lang'=>$lang)),
                           'label'=>Yii::t('messages', 'layout.about')),
                           
	          array("url"=>array(),
	                       "label"=>"Dummy menu",
	              array("url"=>array(
	                           "link"=>"http://www.yiiframework.com",
	                           "htmlOptions"=>array("target"=>"_BLANK")),
	                           "label"=>"Yii Framework"),
	          array("url"=>array(
	                       "route"=>"/product/clothes"),
	                       "label"=>"Clothes",
	          array("url"=>array(
	                       "route"=>"/product/men",
	                       "params"=>array("id"=>3),
	                       "htmlOptions"=>array("title"=>"title")),
	                       "label"=>"Men"),
	            array("url"=>"",
	                         "label"=>"Women",
	                array("url"=>array(
	                             "route"=>"/product/scarves",
	                             "params"=>array("id"=>5)),
	                             "label"=>"Scarves"))),
	              array("url"=>array(
	                           "route"=>"site/menuDoc"),
	                           "label"=>"Disabled Link",
	                           "disabled"=>true),
	                )
          
                      ),
			)
			);
		endif;
		?>
		</nav>
		<?php if(Yii::$app->getSession()->hasFlash('success')): ?>
		<div class="flash-success">
			<?php echo Yii::$app->getSession()->getFlash('success'); ?>
		</div>
		<?php elseif(Yii::$app->getSession()->hasFlash('error')): ?>
		<div class="flash-error">
			<?php echo Yii::$app->getSession()->getFlash('error'); ?>
		</div>
		<?php endif; ?>
		<div id="content">
			<?= $content ?>
		</div>
		<footer>
			Administration developed by <?= Html::a('http://oligalma.com', 'http://oligalma.com', array('target' => '_blank'))?>
			<br/><br/>
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
			">
			<input type="image" src="https://www.paypalobjects.com/en_US/GB/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online.">
			<img alt="" border="0" src="https://www.paypalobjects.com/es_ES/i/scr/pixel.gif" width="1" height="1">
			</form>
		</footer>
	</div>
	<div id="post-box" class="center">

	</div>	
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
<?php
	function printFlags($lang, $paramKeys, $paramValues, $languages)
	{
		foreach($languages as $key => $language)
		{
			$arrayUrl = array();					

			$arrayUrl[0] = Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;
		 	for($i = 0; $i < count($paramKeys); $i++)
				if($paramKeys[$i] !== 'lang')
		 			$arrayUrl[$paramKeys[$i]] = $paramValues[$i];
			$arrayUrl['lang'] = $language;
			
			echo Html::a(Html::img('@web/images/flags/' . $language . '.gif', array('alt' => $language, 'class' => ($lang === $language ? 'selectedflag' : '')))
				,$arrayUrl);
		}
	}
?>
