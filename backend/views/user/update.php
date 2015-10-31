<?php
	use yii\helpers\Url;
	use yii\web\View;
	use yii\helpers\Html;
	use yii\bootstrap\ActiveForm;

	$this->registerJsFile(Url::base() . '/js/pStrength.jquery.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
	$this->registerJs(
	"
		// Password strength meter
	    $('#user-password, #user-repeatpassword').pStrength({
	        'changeBackground'          : false,
	        'onPasswordStrengthChanged' : function(passwordStrength, strengthPercentage) {
	            if ($(this).val()) {
	                $.fn.pStrength('changeBackground', this, passwordStrength);
	            } else {
	                $.fn.pStrength('resetStyle', this);
	            }
	            $('#' + $(this).data('display')).html('(" . Yii::t('messages', 'profile.passwordstrength') . ": ' + strengthPercentage + '%)');
	        },
	    });
	    
	    // Password generator
		$.extend({
		  password: function (length, special) {
		    var iteration = 0;
		    var password = '';
		    var randomNumber;
		    if(special == undefined){
		        var special = false;
		    }
		    while(iteration < length){
		        randomNumber = (Math.floor((Math.random() * 100)) % 94) + 33;
		        if(!special){
		            if ((randomNumber >=33) && (randomNumber <=47)) { continue; }
		            if ((randomNumber >=58) && (randomNumber <=64)) { continue; }
		            if ((randomNumber >=91) && (randomNumber <=96)) { continue; }
		            if ((randomNumber >=123) && (randomNumber <=126)) { continue; }
		        }
		        iteration++;
		        password += String.fromCharCode(randomNumber);
		    }
		    return password;
		  }
		});
		
		$('#generatepasswordbutton').click(function(){
			var password = $.password(10,true);
			$('#generatepasswordtext').val(password);
			$('#user-password').val(password);
			$('#user-repeatpassword').val(password);
			$('#user-password').keyup();
			$('#user-repeatpassword').keyup();
		});
		"
	, View::POS_END, 'userjs');
?>
<?php $form = ActiveForm::begin(['id' => 'user-form']);
		echo $form->errorSummary($model);
		
    	echo $form->field($model, 'username')->textInput(['style'=>'width:100%', 'autocomplete' => 'off']);

    	$field = $form->field($model, 'password');
    	$field->template = '{label}<div style="margin-left:10px" class="left" id="pwdisplay"></div><div class="clear"></div>'
		. Html::button(Yii::t('messages', 'profile.generatepassword'), array('id' => 'generatepasswordbutton'))
		. Html::textInput('','', array('id' => 'generatepasswordtext', 'style' => 'width:120px'))
		. '<div class="clear"></div>'
		. '{input}';
    	echo $field->passwordInput(array('style'=>'width:100%', 'autocomplete' => 'off', 'data-display'=>'pwdisplay'));		

    	
    	$field = $form->field($model, 'repeatPassword');
    	$field->template = '{label}<div style="margin-left:10px" class="left" id="rpwdisplay"></div><div class="clear"></div>{input}'; 
    	echo $field->passwordInput(array('style'=>'width:100%', 'autocomplete' => 'off', 'data-display'=>'rpwdisplay'));

		echo $form->field($model, 'email')->textInput(['style'=>'width:100%', 'autocomplete' => 'off']);
		
	?>
	<div class="center margin-40-0-20-0">
		<?php
			echo Html::submitButton(Yii::t('messages', 'common.save'), ['class' => 'login-item', 'name' => 'login-button'])
		?>
	</div>
<?php ActiveForm::end(); ?>