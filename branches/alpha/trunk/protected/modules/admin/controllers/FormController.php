<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FormController
 *
 * @author abie
 */
class FormController extends AdminController
{
    public  $layout = 'setting';
    
    public function actionIndex(){
        $this->checkAccess('form.view');
        $forms = RecruitmentForm::model()->findAllByRecId($this->rec->id);
        $this->render('index', array('forms' => $forms));
    }
    
    public function actionEdit($fid) {
        $this->checkAccess('form.edit');
        $this->render('edit');
    }
}

?>
