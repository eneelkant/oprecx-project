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
    public $layout = 'setting';
    
    protected function beforeAction($action)
    {
        if ($this->getRec() == NULL) {
            throw new CHttpException('404');
        }
        return parent::beforeAction($action);
    }
    
    public function actionIndex(){
        $this->render('index', array('slots' => InterviewSlot::model()->findAllByRecId($this->rec->id)));
    }
    
    public function actionEdit($id)
    {
        $this->render('edit', array('slot' => InterviewSlot::model()->findById($id)));
    }
}

?>
