<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SlotController
 *
 * @author abie
 */
class SlotController extends AdminController
{
    protected function beforeAction($action)
    {
        if ($this->getRec() == NULL) {
            throw new CHttpException('404');
        }
        $this->layout = 'setting';
        return parent::beforeAction($action);
    }
    
    public function actionIndex(){
        $this->render('index', array('slots' => InterviewSlot::model()->findAllByRecId($this->rec->id)));
    }
}

?>
