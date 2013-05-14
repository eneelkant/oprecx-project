<?php

class DefaultController extends AdminController {
    public $layout = 'standard';
    protected $_mustHaveRec = false;


    public function actionIndex() {
        
        $this->render('index');
    }

}