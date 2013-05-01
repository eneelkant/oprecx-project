<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserController
 *
 * @author abie
 */
class UserController extends CController {
    
     public function init()
     {
         parent::init();
         $this->layout = 'global';
     }
    
    public function actionLogin() {
        if(isset($this->actionParams['nexturl']))
            $nexturl = $this->actionParams['nexturl'];
        else
            $nexturl = array('/user/profile');
        if (! O::app()->user->getIsGuest()) {
            $this->redirect($nexturl);
        }
        
        $model = new UserLoginForm;
        $form = $model->createForm();
        $form->action['nexturl'] = CHtml::normalizeUrl($nexturl);

        //var_dump($this->actionParams);
        //if ($form->submitted('login')) exit;
        if ($form->submitted('login') && $form->validate() && $model->login()) {
            $this->redirect($nexturl);
        }
        else {
            $this->render('login', array('form' => $form));
        }
        
    }
    
    public function actionRegister() {
        if(isset($this->actionParams['nexturl']))
            $nexturl = $this->actionParams['nexturl'];
        else
            $nexturl = array('/user/profile');

        $model = new UserRegisterForm;
        $form = $model->createForm();
        $form->action['nexturl'] = CHtml::normalizeUrl($nexturl);
        
        //var_dump($this->actionParams);
        if ($form->submitted('register') && $form->validate() && $model->register()) {
            $this->redirect($nexturl);
        }
        else {
            $this->render('register', array('form' => $form));
        }
    }
    
    public function actionLogout() {
        O::app()->user->logout();
        $this->redirect(O::app()->homeUrl);
    }
}

?>
