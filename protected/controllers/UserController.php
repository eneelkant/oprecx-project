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
    
    public function actionLogin() {
        $model = new UserLoginForm;
        $form = new CForm(UserLoginForm::$formConfig, $model);
        if(isset($this->actionParams['nexturl']))
            $nexturl = $this->actionParams['nexturl'];
        else
            $nexturl = array('/user/index');
        $form->action['nexturl'] = $nexturl;
        
        //var_dump($this->actionParams);
        if ($form->submitted('login') && $form->validate() && $model->login()) {
            $this->redirect($nexturl);
        }
        else {
            $this->render('login', array('form' => $form));
        }
        
    }
    
    public function actionRegister() {
        $model = new UserRegistrationForm;
        $form = new CForm(UserRegistrationForm::$formConfig, $model);
        if(isset($this->actionParams['nexturl']))
            $nexturl = $this->actionParams['nexturl'];
        else
            $nexturl = array('/user/index');
        $form->action['nexturl'] = $nexturl;
        
        //var_dump($this->actionParams);
        if ($form->submitted('register') && $form->validate() && $model->register()) {
            $this->redirect($nexturl);
        }
        else {
            $this->render('register', array('form' => $form));
        }
    }
}

?>
