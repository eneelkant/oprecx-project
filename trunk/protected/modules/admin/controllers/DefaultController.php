<?php

class DefaultController extends Controller {

    public function actionIndex() {
        // $this->layout = 'column2';
        $this->render('index');
    }

}