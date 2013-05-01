<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AdminController
 *
 * @property Recruitment $rec Description
 * @property Division[] $divList Description
 * 
 * @author abie
 */
class AdminController extends CController
{
    protected $_rec = FALSE;
    protected $_divList = FALSE;
    protected $_msg = array();
    
    public $helpView = NULL;


    public function createUrl($route, $params = array (), $ampersand = '&')
    {
        if ($route[0] != '/' && !isset($params['rec']) && isset($this->actionParams['rec'])) {
            $params['rec'] = $this->actionParams['rec'];
        }
        return parent::createUrl($route, $params, $ampersand);
    }
    
    /**
     * 
     * @return Recruitment
     */
    public function &getRec() {
        if ($this->_rec === FALSE) {
            if (isset($this->actionParams['rec'])){
                $this->_rec = Recruitment::model()->findByName($this->actionParams['rec']);
            }
            else 
                $this->_rec = NULL;
        }
        return $this->_rec;
    }
    
    /**
     * 
     * @return Division[]
     */
    public function &getDivList() {
        if ($this->_divList === FALSE) {
            if (($rec = $this->getRec()) != NULL) {
                $this->_divList = Division::model()->findAllByRecId($rec->id);
            } else {
                // FIXME null ??
                $this->_divList = array();
            }
        }
        return $this->_divList;
    }
    
    
    /**
     * 
     * @return Recruitment[]
     */
    public function getMyRecruitments() {        
        return Recruitment::model()->populateRecords(
                CDbCommandEx::create()
                    ->select('o.id, o.name, o.full_name')
                    ->from(TableNames::RECRUITMENT . ' o')
                    ->join(TableNames::REC_ADMIN . ' oa', 'oa.rec_id = $o.id')
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
