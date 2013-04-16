<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OprecxWebApplication
 *
 * @author abie
 */
class OprecxHelper
{
    public static function changeLanguage($lang) {
        setcookie('lang', $lang, time() + 3600 * 24 * 356, Yii::app()->request->baseUrl . '/');
    }
    
    /**
     * 
     * @param CWebApplication $app
     */
    public static function initializeApp($app) {
        $app->setLanguage(isset($_COOKIE['lang']) ? $_COOKIE['lang'] : 'id');
        if (YII_DEBUG) {
            $app->messages->onMissingTranslation = array('CPhpMessageTranslator', 'appendMessage');
            $app->setModules(
                    array('gii' => array(
                        'class' => 'system.gii.GiiModule',
                        'password' => 'gii',
                    ))
            );
            
        }
    }
}

?>
