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
        setcookie('lang', $lang, 3600 * 24 * 356);
    }
    
    /**
     * 
     * @param CWebApplication $app
     */
    public static function initializeApp($app) {
        $app->setLanguage(isset($_COOKIE['lang']) ? $_COOKIE['lang'] : 'id');
        if (YII_DEBUG) {
            $app->messages->onMissingTranslation = array('CPhpMessageTranslator', 'appendMessage');
            $app->modules['gii'] = array(
                'class' => 'system.gii.GiiModule',
                'password' => 'gii',
                // If removed, Gii defaults to localhost only. Edit carefully to taste.
                'ipFilters' => array('127.0.0.1', '::1'),
                'generatorPaths' => array(
                    //'ext.giix-core', // giix generators
                )
            );
            
        }
    }
}

?>
