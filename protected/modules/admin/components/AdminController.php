<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AdminController
 *
 * @property Organizations $org Description
 * @property Divisions[] $divList Description
 * 
 * @author abie
 */
class AdminController extends CController
{
    protected $_org = FALSE;
    protected $_divList = FALSE;
    protected $_msg = array();
    
    public $helpView = NULL;


    public function createUrl($route, $params = array (), $ampersand = '&')
    {
        if ($route[0] != '/' && !isset($params['org']) && isset($this->actionParams['org'])) {
            $params['org'] = $this->actionParams['org'];
        }
        return parent::createUrl($route, $params, $ampersand);
    }
    
    /**
     * 
     * @return Organizations
     */
    public function &getOrg() {
        if ($this->_org === FALSE) {
            if (isset($this->actionParams['org'])){
                $this->_org = Organizations::model()->findByName($this->actionParams['org']);
            }
            else 
                $this->_org = NULL;
        }
        return $this->_org;
    }
    
    /**
     * 
     * @return Divisions[]
     */
    public function &getDivList() {
        if ($this->_divList === FALSE) {
            if (($org = $this->getOrg()) != NULL) {
                $this->_divList = Divisions::model()->findAllByOrg($org->id);
            } else {
                // FIXME null ??
                $this->_divList = array();
            }
        }
        return $this->_divList;
    }
    
    
    /**
     * 
     * @return Organizations[]
     */
    public function getMyOrgs() {        
        return Organizations::model()->populateRecords(
                CDbCommandEx::create()
                    ->select('o.id, o.name, o.full_name')
                    ->from(TableNames::ORGANIZATIONS . ' o')
                    ->join(TableNames::ORG_ADMINS . ' oa', 'oa.org_id = $o.id')
                    ->where('$oa.user_id = :user_id', array('user_id' => O::app()->user->id))
                    ->order('o.updated DESC')
                    ->queryAll()
                );
        
    }
    
    
    public function addMsg($msg, $text)
    {
        $this->_msg[$msg] = $text;
    }
}

?>
