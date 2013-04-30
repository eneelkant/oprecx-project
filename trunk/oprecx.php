<?php

/*
 * This file contains wrapper classes for YiiBase and CWebApplication
 */

/**
 * Oprecx Main Class
 *
 */
class O extends YiiBase
{

    /**
     * 
     * @param type $config
     * @see CWebApplication
     * @return OprecxWebApplication
     */
    public static function createOprecxWebApplication($config = null)
    {
        return parent::createApplication('OprecxWebApplication', $config);
    }

    /**
     * @todo Hapus ketika selesai development
     * @return OprecxWebApplication applications
     */
    public static function app()
    {
        return parent::app();
    }

}

/**
 * Oprecx Application
 * 
 * @property Bootstrap $bootstrap Description
 */

class OprecxWebApplication extends CWebApplication
{

    public function changeLanguage($new_lang)
    {
        setcookie('lang', $new_lang, time() + 3600 * 24 * 356, $this->getHomeUrl());
    }

    private function initLanguage()
    {
        $params        = $this->getParams();
        $supportedLang = $params['supportedLang'];
        $curLang       = $params['defaultLang'];

        if (isset($_COOKIE['lang']) && isset($supportedLang[$_COOKIE['lang']])) {
            $curLang = $_COOKIE['lang'];
        }
        elseif (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $langMap = $params['langMap'];

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
        $this->setLanguage($curLang);
    }

    protected function init()
    {
        parent::init();
        $this->initLanguage();
        if (!YII_DEBUG) {
            $this->getClientScript()->scriptMap = array (
                'jquery.min.js'      => '//cdnjs.cloudflare.com/ajax/libs/jquery/1.9.1/jquery.min.js',
                'jquery-ui.min.js'   => '//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js',
                'bootstrap.min.js'   => '//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.3.1/js/bootstrap.min.js',
                'bootstrap.min.css'  => '//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.3.1/css/bootstrap.min.css',
                'bootstrap-responsive.min.css' => '//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.3.1/css/bootstrap-responsive.min.css',
                'jquery.form.min.js' => '//cdnjs.cloudflare.com/ajax/libs/jquery.form/3.24/jquery.form.min.js',
                'jquery.form.js' => '//cdnjs.cloudflare.com/ajax/libs/jquery.form/3.24/jquery.form.min.js',
                'wysihtml5-0.3.0.js' => '//cdnjs.cloudflare.com/ajax/libs/wysihtml5/0.3.0/wysihtml5.min.js',
                'date.js' => '//cdnjs.cloudflare.com/ajax/libs/datejs/1.0/date.min.js',
                'bootstrap-datepicker.css' => '//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.0.2/css/bootstrap-datepicker.min.css',
                'bootstrap-datepicker.js' => '//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.0.2/js/bootstrap-datepicker.min.js',
                'bootstrap-datepicker.id.js' => '//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.0.2/js/locales/bootstrap-datepicker.id.min.js',
            );
        }
        
        // TODO: remove debug specific options and add that to plugins
        if (YII_DEBUG) {
            $this->messages->onMissingTranslation = array ('CPhpMessageTranslator', 'appendMessage');
            $this->setModules(
                    array ('gii' => array (
                            'class'    => 'system.gii.GiiModule',
                            'password' => 'gii',
                        ))
            );
            $this->cache->hashKey                 = false;
        }
    }

}