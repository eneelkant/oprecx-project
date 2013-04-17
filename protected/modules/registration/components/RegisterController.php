<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RegisterController
 *
 * @author abie
 * @property Organizations $org Current org elements
 */
abstract class RegisterController extends Controller {
    
    /** @var Organizations $org current organization */
    private $_org;
    public $page_class;
    public $isWizard = false;
    
    public function getURL($actions, $args = array(), $relative = true) {
        $theArg = array($actions, 'org_name' => $this->actionParams['org_name']);
        //if ($this->isWizard) $theArg['wiz'] = 1;
        
        $url = CHtml::normalizeUrl(array_merge($theArg, $args));
        return $relative ? $url : Yii::app()->getRequest()->getHostInfo() . $url;
    }

    public function getOrg() {
        return $this->_org;
    }

    public function init (){
        $params = $this->actionParams;
        if (isset($params['org_name'])) {
            $this->_org = Organizations::getByName($params['org_name']); // Organizations::model()->findByAttributes(array('name' => $params['org']));
            if (null == $this->_org) {
                throw new CHttpException(404,Yii::t('oprecx','Organization {org} Not Found.', array('{org}' => $params['org_name'])));
            }
            
        } else {
            throw new CHttpException(404,Yii::t('oprecx','Page Not Found "{action}".'));
        }
        
        //$this->isWizard = isset($params['wiz']) && $params['wiz'] == 1;
        
        //var_dump($this->actionParams);
        
    }
}

?>
