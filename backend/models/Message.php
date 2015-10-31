<?php

namespace app\models;

use Yii;


class Message
{
	public $language;
	public $file;
	public $messageId;
	public $message;
	
	public static function findAll($language, $file)
	{
		// Read messages file
		$fileValues = array();
		$fileKeys = array();
		$message = '';
		if($language != '' && $file != '')
		{
			$handle = fopen(Yii::getAlias('@frontend') . '/messages/' . $language . '/' . $file, "r");
			if ($handle) {
				$i = 0;
			    while (($line = fgets($handle)) !== false) {
			        if(strpos($line,'\'') > -1 && strpos($line,'=>') > -1)
					{
						$fileKeys[] = substr($line, self::strposX($line,'\'',1) + 1,  self::strposX($line,'\'',2) - self::strposX($line,'\'',1) - 1);				
						$fileValues[] = substr($line, self::strposX($line,'\'',3) + 1,  self::strposX($line,'\'',4) - self::strposX($line,'\'',3) - 1);
						
						$i++;
					}
			    }
			
			    fclose($handle);
			} else {
			    // error opening the file.
			} 			
		}
		
		$messages = array();
		$messages[0] = $fileKeys;
		$messages[1] = $fileValues;
		
		return $messages;
	}
	
	public static function find($language, $file, $messageId)
	{
		$handle = fopen(Yii::getAlias('@frontend') . '/messages/' . $language . '/' . $file, "r");
		if ($handle) {
			$i = 0;
		    while (($line = fgets($handle)) !== false) {
		        if(strpos($line,'\'') > -1 && strpos($line,'=>') > -1)
				{
					if($i == $messageId)
						return substr($line, self::strposX($line,'\'',3) + 1,  self::strposX($line,'\'',4) - self::strposX($line,'\'',3) - 1);
					
					$i++;
				}
		    }
		
		    fclose($handle);
		} else {
		    // error opening the file.
		} 		
	}
	
	public function save()
	{
		$fileContents = '';
		$handle = fopen(Yii::getAlias('@frontend') . '/messages/' . $this->language . '/' . $this->file, "r");
		if ($handle) {
			$i = 0;
		    while (($line = fgets($handle)) !== false) {
		        if(strpos($line,'\'') > -1 && strpos($line,'=>') > -1)
				{
					if($i == $this->messageId)
					{
						$newMessage = str_replace('\'','&#039;',$this->message);					
						$key = substr($line, self::strposX($line,'\'',1) + 1,  self::strposX($line,'\'',2) - self::strposX($line,'\'',1) - 1);			
						$line = '\'' . $key . '\' => \'' . $newMessage . '\',' . "\n";						
					}
						
					$i++;
				}
				$fileContents .= $line;
		    }
		
		    fclose($handle);
		} else {
		    // error opening the file.
		}
		file_put_contents(Yii::getAlias('@frontend') . '/messages/' . $this->language . '/' . $this->file, $fileContents);		
	}
	
	public static function getLanguages()
	{
		$path = Yii::getAlias('@frontend') . '/messages';
		$languages = scandir($path);
		foreach ($languages as $key => $value) {
		    if ($value === '.' or $value === '..')
				unset($languages[$key]);
		}
		$languages = array_combine($languages, $languages);
		
		return $languages;		
	}

	public static function getFiles($language)
	{
		// Get message files of a language
		$path = Yii::getAlias('@frontend') . '/messages/' . $language;
		$files = array();
		$files = scandir($path);
		foreach ($files as $key => $value) {
		    if ($value === '.' or $value === '..')
				unset($files[$key]);
		}
		$files = array_combine($files, $files);
		
		return $files;	
	}
	
	/**
	 * Find the position of the Xth occurrence of a substring in a string
	 * @param $haystack
	 * @param $needle
	 * @param $number integer > 0
	 * @return int
	 */
	public static function strposX($haystack, $needle, $number){
	    if($number == '1'){
	        return strpos($haystack, $needle);
	    }elseif($number > '1'){
	        return strpos($haystack, $needle, self::strposX($haystack, $needle, $number - 1) + strlen($needle));
	    }else{
	        return error_log('Error: Value for parameter $number is out of range');
	    }
	}
}
