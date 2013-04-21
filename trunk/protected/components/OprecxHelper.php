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
        $supportedLang = $app->params['supportedLang'];
        $curLang = $app->params['defaultLang'];
        
        if (isset($_COOKIE['lang']) && isset($supportedLang[$_COOKIE['lang']])) {
            $curLang = $_COOKIE['lang'];
        }
        elseif (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $langMap = $app->params['langMap'];
            
            foreach (explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']) as $lang) {
                if (($ipos = strpos($lang, ';')) > 0) $lang = substr($lang, 0, $ipos);
                $lang = trim(strtolower($lang));
                if (isset($langMap[$lang])) $lang = $langMap[$lang];
                
                if (isset($supportedLang[$lang])) {
                    $curLang = $lang;
                    //self::changeLanguage($lang);
                    break;
                }
            }
        }
        $app->language = $curLang;
        
        if (YII_DEBUG) {
            $app->messages->onMissingTranslation = array('CPhpMessageTranslator', 'appendMessage');
            $app->setModules(
                    array('gii' => array(
                        'class' => 'system.gii.GiiModule',
                        'password' => 'gii',
                    ))
            );
            $app->cache->hashKey = false;
        }
    }
}

?>
