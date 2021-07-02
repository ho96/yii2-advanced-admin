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
