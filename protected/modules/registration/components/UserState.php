<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserState
 *
 * @author abie
 */
class UserState extends CComponent
{
    private $_userId = NULL;
    private $_orgId = NULL;
    private $_modified = false;
    private $_data = array();

    private static $_cacheName = 'oprecx:UserState:userId=%d:orgId=%d';
    private static $_loaded = array();
    
    /**
     * 
     * @param int $userId
     * @param int $orgId
     * @param string|array $components
     */    
    public static function invalidate($userId, $orgId, $components = null)
    {
        
        if (is_string($components) || is_array($components)) {
            $obj = self::load($userId, $orgId);
            if (is_array($components)) {
                foreach ($components as $name) {
                    if (isset($obj->_data[$name])) unset($obj->_data[$name]);
                }
            } else {
                if (isset($obj->_data[$components])) unset($obj->_data[$components]);
            }
            $obj->setModified();
        } else {
            Yii::app()->cache->delete(sprintf(self::$_cacheName, $userId, $orgId));
            if (isset(self::$_loaded["{$userId}:{$orgId}"])) unset(self::$_loaded["{$userId}:{$orgId}"]);
        }
        
    }

        /**
     * 
     * @param int $userId
     * @param int $orgId
     * @return UserState Description
     */
    public static function &load($userId, $orgId) {
        //$cacheName = "oprecx:UserState:userId={$userId}:orgId={$orgId}";
        $objName = "{$userId}:{$orgId}";
        if (!isset(self::$_loaded[$objName])) {
            if (false === ($data = Yii::app()->cache->get(sprintf(self::$_cacheName, $userId, $orgId)))) {
                $data = array();
                $obj->setModified();
            }
            $obj = new UserState;
            $obj->_userId = $userId;
            $obj->_orgId = $orgId;
            $obj->_data = $data;
            self::$_loaded[$objName] =& $obj;
        }
        return self::$_loaded[$objName];
    }
    
    private function setModified() {
        if (! $this->_modified) {
            Yii::app()->onEndRequest = array($this, 'save');
            $this->_modified = true;
        }
    }
    
    public function save() {
        if ($this->_modified) {
            Yii::app()->cache->set(sprintf(self::$_cacheName, $this->_userId, $this->_orgId), $this, 60);
        }
    }


    /**
     * 
     * @return int
     */
    public function getUserId()
    {
        return $this->_userId;
    }
    
    /**
     * 
     * @return int
     */
    public function getOrgId()
    {
        return $this->_orgId;
    }
    
    
    /**
     * 
     * @return array
     */
    public function getDivisionChoices()
    {
        if (! isset($this->_data['division'])) {
            $this->_data['division'] = Yii::app()->db->createCommand()
                    ->select('d.div_id')
                    ->from(TableNames::DIVISION_CHOICES . ' dc')
                    ->join(TableNames::DIVISION_CHOICES . ' d', 
                            'dc.div_id = d.div_id AND d.org_id = :org_id AND d.enabled = 1')
                    ->order('dc.weight, d.weight, d.name')
                    ->where('dc.user_id = :user_id')
                    ->queryColumn(array('org_id' => $this->_orgId, 'user_id' => $this->_userId));
            $this->setModified();
        }
        
        return $this->$this->_data['division'];
    }
    
    public function getFormStatus() 
    {
        if (! isset($this->_data['form'])) {
            $reader = Yii::app()->db->createCommand()
                    ->select('f.elm_id AS form_id,  COUNT(*) AS unfilled')
                    ->from(TableNames::FORMS . ' f')
                    ->join(TableNames::ORG_ELMS . ' oe',
                            'oe.elm_id = f.elm_id AND oe.org_id = :org_id')
                    ->join(TableNames::DIVISION_ELMS . ' de',
                            'de.elm_id = oe.elm_id')
                    ->join(TableNames::DIVISION_CHOICES . ' dc',
                            'dc.div_id = de.div_id AND dc.user_id = :user_id')
                    ->leftJoin(TableNames::FORM_FIELDS . ' ff', 
                            'ff.form_id = f.elm_id AND ff.required')
                    ->leftJoin(TableNames::FORM_VALUES . ' fv', 
                            'fv.field_id = ff.field_id AND fv.user_id = :user_id')
                    ->order('f.elm_id, oe.name')
                    //->where('dc.user_id = :user_id')
                    ->query(array('org_id' => $this->_orgId, 'user_id' => $this->_userId));
            $form = array();
            foreach ($reader as $row) {
                $form[$row['form_id']] = $row['unfilled'];
            }
            $this->_data['form'] = $form;
            $this->setModified();
        }
        
        return $this->$this->_data['form'];
    }
}


?>
