<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RegisterController
 *
 * @author abie
 * @property string $orgName Current organization's name
 */
abstract class RegisterController extends Controller {
    
    private $_orgName;
    
    public function getOrgName() {
        if (empty($this->_orgName)) {
            $this->_orgName = 'Nama Organisasi';
        }
        return $this->_orgName;
    }
    
    public function init (){
        $this->pageTitle = $this->orgName . ' | ' . Yii::t('register', 'Registration');
    }
}

?>
