<?php
class CPhpMessageTranslator extends CApplicationComponent{
	public static $message = array();
	public static $init = FALSE;
    
    public static function appendMessage(CMissingTranslationEvent $event){
    	self::$message[$event->language][$event->category][$event->message] = '';
    	if (! self::$init){
    		Yii::app()->attachEventHandler('onEndRequest', array('CPhpMessageTranslator', 'writeMessage'));
    		self::$init = TRUE;
    	}
    }
    public static function writeMessage(){
    	foreach (self::$message as $lang => $data){
    		$dir = Yii::getPathOfAlias('application.messages') . DIRECTORY_SEPARATOR . $lang;
    		if (! is_dir($dir)) mkdir($dir, 0777, TRUE);
    		foreach ($data as $category => $untranslated){
    			$fileName = $dir . DIRECTORY_SEPARATOR . $category . '.php';
   				//ksort($untranslated);
				//$merged = $untranslated;

    			if(is_file($fileName))
    			{
    				$translated=require($fileName);
    				if (! is_array($translated)) $translated = array();
                    foreach ($translated as $k => $v) {
                        if ('' === $v) {
                            $untranslated[$k] = '';
                            unset($translated[$k]);
                        }
                    }
    				//$merged = array_merge($untranslated, $translated);
    			}
                ksort($untranslated);
                $fh = fopen($fileName, 'w');
                fwrite($fh, "<?php return array(\n\n///////////   UNTRANSLATED //////////// \n");
                self::writeKeyPair($fh, $untranslated);
                
                fwrite($fh, "\n\n\n//Translated\n");
                if (isset($translated))
                    self::writeKeyPair($fh, $translated);
                fwrite($fh, ");\n");
                fclose($fh);
                //$merged = array_merge($untranslated, $translated);
    			// $array=str_replace("\r",'',var_export($merged,true));
                //file_put_contents($fileName, '<?php return ' . var_export($merged, true) . ';');
    		}
    	}
    }
    
    private static function writeKeyPair($fh, $array) {
        foreach($array as $k => $v) {
            fwrite($fh, sprintf("  '%s'=> '%s',\n", addcslashes($k, "'\\"), addcslashes($v, "'\\")));
        }
    }
}