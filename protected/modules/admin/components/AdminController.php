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
    protected $_rule = FALSE;
    protected $_mustHaveRec = TRUE;


    public $helpView = NULL;
    


    public function createUrl($route, $params = array (), $ampersand = '&')
    {
        if ($route[0] != '/' && !isset($params['rec']) && isset($this->actionParams['rec'])) {
            $params['rec'] = $this->actionParams['rec'];
        }
        return parent::createUrl($route, $params, $ampersand);
    }
    
    protected function checkAccess($action, $throwError = true) {
        if (!$this->_rule) $this->_rule = new AdminRule($this->rec->id, O::app ()->getUser()->getId ());
        if (! $this->_rule->canI($action)) {
            if ($throwError)
                throw new CHttpException(403, O::t('You have no permission to access this action'));
            else return false;
        }
        return true;
    }
    
    public function init()
    {
        if ($this->_mustHaveRec && !isset($this->actionParams['rec'])) {
            throw new CHttpException(403);
        }
        parent::init();
    }
    
    /**
     * 
     * @return Recruitment
     */
    public function getRec() {
        if ($this->_rec) return $this->_rec;
        
        if (isset($this->actionParams['rec'])){
            /* @var $rec Recruitment */
            if (($rec = Recruitment::model()->findByName($this->actionParams['rec'])) == NULL) {
                throw new CHttpException(404, 
                        O::t('oprecx', 'Recruitment "{rec}" can not be found.', 
                                array('{rec}' => $this->actionParams['rec']))
                );
            } else {
                O::app()->setTimeZone($rec->timezone);
            }
        }
        else {
            $rec = NULL;
        }
        
        return $this->_rec = $rec;
    }
    
    /**
     * 
     * @return Division[]
     */
    public function getDivList() {
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
    public function getMyRecruitments($limit = FALSE) {        
        return Recruitment::model()->populateRecords(
                CDbCommandEx::create()
                    ->select('o.id, o.name, o.full_name')
                    ->from(TableNames::RECRUITMENT_as('o'))
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
