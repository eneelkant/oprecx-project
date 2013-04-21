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
            $obj = new UserState;
            $obj->_userId = $userId;
            $obj->_orgId = $orgId;
            if (false === ($data = Yii::app()->cache->get(sprintf(self::$_cacheName, $userId, $orgId)))) {
                $data = array();
                $obj->setModified();
            }
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
            Yii::app()->cache->set(sprintf(self::$_cacheName, $this->_userId, $this->_orgId), $this->_data, 60);
            $this->_modified = false;
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
     * @param CDbDataReader $reader
     * @param string $pk
     * @return Object[]
     */
    private static function &arrayToObject($reader, $pk = null)
    {
        $arr = array();
        foreach ($reader as $row) {
            if ($pk) {
                $arr[$row[$pk]] = (Object) $row;
            } else {
                $arr[] = (Object) $row;
            }
        }
        return $arr;
    }
    
    /**
     * 
     * @return UserStateDivisionChoice[]
     */
    public function getDivisionChoices()
    {
        if (! isset($this->_data['division'])) {
            $this->_data['division'] = self::arrayToObject(
                    Yii::app()->db->createCommand()
                        ->select('d.div_id, d.name AS div_name')
                        ->from(TableNames::DIVISION_CHOICES . ' dc')
                        ->join(TableNames::DIVISIONS . ' d', 
                                'dc.div_id = d.div_id AND d.org_id = :org_id AND d.enabled = 1')
                        ->order('dc.weight, d.weight, d.name')
                        ->where('dc.user_id = :user_id')
                        ->queryAll(true, array('org_id' => $this->_orgId, 'user_id' => $this->_userId))
                    );
            $this->setModified();
        }
        
        return $this->_data['division'];
    }
    
    /**
     * 
     * @return UserStateFormStatus[]
     */
    public function getFormStatus() 
    {
        if (! isset($this->_data['form'])) {
            $command = Yii::app()->db->createCommand()
                    ->select('f.elm_id AS form_id, oe.name as form_name,  (COUNT(ff2.*) - COUNT(fv.value)) = 0 AS filled')
                    ->from(TableNames::FORMS . ' f')
                    ->join(TableNames::ORG_ELMS . ' oe',
                            'oe.elm_id = f.elm_id AND oe.org_id = :org_id')
                    ->join(TableNames::DIVISION_ELMS . ' de',
                            'de.elm_id = oe.elm_id')
                    ->join(TableNames::DIVISION_CHOICES . ' dc',
                            'dc.div_id = de.div_id AND dc.user_id = :user_id')
                    ->leftJoin(TableNames::FORM_FIELDS . ' ff', 
                            'ff.form_id = f.elm_id AND ff.required = 1')
                    ->leftJoin(TableNames::FORM_FIELDS . ' ff2', 
                            'ff2.form_id = f.elm_id AND ff2.required = 1')
                    ->leftJoin(TableNames::FORM_VALUES . ' fv', 
                            'fv.field_id = ff.field_id AND fv.user_id = :user_id')
                    ->order('oe.weight, oe.name')
                    ->group('f.elm_id, oe.weight, oe.name');
            $this->_data['form'] = self::arrayToObject(
                    $command->queryAll(true, array('org_id' => $this->_orgId, 'user_id' => $this->_userId))
                );
            $this->setModified();
        }
        
        return $this->_data['form'];
    }
    
    public function getSelectedInterviewSlot() {
        if (!isset($this->_data['slots'])) {
            $this->_data['slots'] = Yii::app()->db->createCommand()
                    ->selectDistinct('iss.elm_id as slot_id, oe.name AS slot_name, ius.time, oe.weight')
                    ->from(TableNames::INTERVIEW_SLOTS . ' iss')
                    ->join(TableNames::ORG_ELMS . ' oe',
                            'oe.elm_id = iss.elm_id AND oe.org_id = :org_id')
                    ->join(TableNames::DIVISION_ELMS . ' de',
                            'de.elm_id = oe.elm_id')
                    ->join(TableNames::DIVISION_CHOICES . ' dc',
                            'dc.div_id = de.div_id AND dc.user_id = :user_id')
                    ->leftJoin(TableNames::INTERVIEW_USER_SLOTS . ' ius', 
                            'ius.slot_id = iss.elm_id AND ius.user_id = :user_id')
                    ->order('oe.weight, oe.name')
                    //->where('dc.user_id = :user_id')
                    ->queryAll(true, array('org_id' => $this->_orgId, 'user_id' => $this->_userId));
            $this->setModified();
        }
        return self::arrayToObject($this->_data['slots']);
    }
}


?>
