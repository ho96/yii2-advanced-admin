<?php
namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\validators\RegularExpressionValidator;
use yii\validators\StringValidator;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
	
	public $repeatPassword;

	/**
	 * @return string the associated database table name
	 */
    public static function tableName()
    {
        return '{{%user}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('username', 'match', 'pattern'=>'/^([0-9a-z]+)$/'),
            array(array('password', 'repeatPassword', 'email'), 'string', 'length' => array(6, 100)),
			array('password', 'compare', 'compareAttribute'=>'repeatPassword'),
            array('username', 'string', 'length' => array(5, 100)),
			array(array('username', 'email'), 'unique'),
			array(array('username', 'email'), 'required'),
			array(array('username', 'password'),'filter','filter'=>'\yii\helpers\HtmlPurifier::process'),
			array('email', 'email'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('messages', 'profile.id'),
			'username' => Yii::t('messages', 'profile.username'),
			'password' => Yii::t('messages', 'profile.password'),
			'email' => Yii::t('messages', 'profile.email'),
			'repeatPassword' => Yii::t('messages', 'profile.repeatpassword'),
		);
	}
	
	public function beforeSave()
	{
		$this->username = trim(strtolower($this->username));
		
		if($this->password === ''){
			$model2 = User::find()->andWhere(['id' => $this->id])->one();
			$this->password_hash = $model2->password_hash;
		}
	
		return true;
	}

	public function afterSave()
	{
		if($this->password != null)
		{
			$phpLiteConfig = 'lib/phpliteadmin/phpliteadmin.config.php';
			$phpLiteConfigContents = file_get_contents($phpLiteConfig);
			$phpLiteConfigNewContents = preg_replace('/\$password ?= ?\\\'(.*)\\\' ?;/', '$password = \''. addslashes($this->password) . '\';', $phpLiteConfigContents);
			file_put_contents($phpLiteConfig, $phpLiteConfigNewContents);
		}		
		
		return true;
	}

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

	
    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }


    public function getPassword()
    {
        return '';
    }
	
    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
    	$this->password = $password;
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
}
