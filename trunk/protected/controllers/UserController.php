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
        $form = new CForm(UserLoginForm::$formData, $model);
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
}

?>
