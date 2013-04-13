<?php

class SiteController extends Controller
{
    
    public function init()
     {
         parent::init();
         $this->layout = 'global';
     }

    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array (
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array (
                'class'     => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page'    => array (
                'class' => 'CViewAction',
            ),
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {
        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'
        $cache_name = 'oprecx:Organizations:active10';
        if (($orgs = Yii::app()->cache->get($cache_name)) == false) {
            $orgs = Organizations::model()->findAll(array (
                'condition' => 'reg_time_begin <= CURRENT_TIMESTAMP AND reg_time_end >= CURRENT_TIMESTAMP',
                'order'     => 'reg_time_end DESC',
                'limit'     => 10,
            ));
            Yii::app()->cache->set($cache_name, $orgs);
        }        
        $this->render('index', array ('orgs' => $orgs));
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest) echo $error['message'];
            else $this->render('error', $error);
        }
    }
    
    public function actionLang($locale, $return)
    {
        //Yii::app()->session->add('lang', $locale);
        $cookies= new CHttpCookie('lang', $locale);
        $cookies->expire = time() + 31104000;
        Yii::app()->request->cookies['lang'] = $cookies;
        $this->redirect($return);
    }
    

}