<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RegisterController
 *
 * @author abie
 * @property Recruitment $rec Current rec elements
 */
abstract class RegisterController extends Controller {
    
    private $_rec;
    public $page_class;
    public $isWizard = false;
    
    public function getURL($actions, $args = array(), $relative = true) {
        $theArg = array($actions, 'rec_name' => $this->actionParams['rec_name']);
        //if ($this->isWizard) $theArg['wiz'] = 1;
        
        $url = CHtml::normalizeUrl(array_merge($theArg, $args));
        return $relative ? $url : O::app()->getRequest()->getHostInfo() . $url;
    }

    public function getRec() {
        return $this->_rec;
    }

    public function init (){
        $params = $this->actionParams;
        if (isset($params['rec_name'])) {
            $this->_rec = Recruitment::model()->findByName($params['rec_name']); // Organizations::model()->findByAttributes(array('name' => $params['org']));
            if (null == $this->_rec) {
                throw new CHttpException(404,O::t('oprecx','Recruitment {rec} Not Found.', array('{rec}' => $params['rec_name'])));
            }
            
        } else {
            throw new CHttpException(404,O::t('oprecx','Page Not Found "{action}".'));
        }
        
        //$this->isWizard = isset($params['wiz']) && $params['wiz'] == 1;
        
        //var_dump($this->actionParams);
        
    }
}

?>
