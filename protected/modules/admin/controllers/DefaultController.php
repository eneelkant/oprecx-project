<?php

class DefaultController extends AdminController {
     public function init()
     {
         parent::init();
         $this->layout = 'standard';
     }

    public function actionIndex() {
        
        $this->render('index');
    }

}