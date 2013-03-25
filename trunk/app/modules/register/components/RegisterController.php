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
    /** @var Organizations $org current organization */
    public $org;
    
    public function getOrgName() {
        if (empty($this->_orgName)) {
            $this->_orgName = 'Nama Organisasi';
        }
        return $this->_orgName;
    }
    
    
    public function getURL($actions, $args = array()) {
        $args[0] = $actions;
        $args['org'] = $this->actionParams['org'];
        return CHtml::normalizeUrl($args);
    }



    public function init (){
        $params = $this->actionParams;
        if (isset($params['org'])) {
            $this->org = Organizations::getByName($params['org']); // Organizations::model()->findByAttributes(array('name' => $params['org']));
            if (null == $this->org) {
                throw new CHttpException(404,Yii::t('oprecx','Organization {org} Not Found.', array('{org}' => $params['org'])));
            }
            $this->pageTitle = $this->orgName . ' | ' . Yii::t('oprecx', 'Registration');
        } else {
            throw new CHttpException(404,Yii::t('oprecx','Page Not Found "{action}".'));
        }
        
        //var_dump($this->actionParams);
        
    }
}

?>
