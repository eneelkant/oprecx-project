<?php

class DefaultController extends RegisterController {

    public function actionIndex($org) {
        //$org = Organizations::model()->findByPk($org_id);
        $org = Organizations::model()->find('name=:name', array('name' => $org));
        $this->render('index', array('org' => $org));
    }
    
    public function actionDivision($org) {
        /** @var Organizations */
        $org = Organizations::model()->with('divisions')->find('t.name=:name', array('name' => $org));
        if (isset($_POST['division'])){
            // TODO: cek apakah pilihan divisi ada di organisasi, cek juga jumlah pilihan divisi
            
            $this->redirect(array('form', 'org' => $org->name));
        }
        $this->render('division', array('org' => $org));
        
    }
    
    public function actionForm($org) {
        
    }

}