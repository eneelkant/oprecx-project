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
    private $_recId = NULL;
    private $_modified = false;
    private $_data = array();

    private static $_cacheName = 'oprecx:UserState:userId=%d:recId=%d';
    private static $_loaded = array();
    
    /**
     * 
     * @param int $userId
     * @param int $recId
     * @param string|array $components
     */    
    public static function invalidate($userId, $recId, $components = null)
    {
        
        if (is_string($components) || is_array($components)) {
            $obj = self::load($userId, $recId);
            if (is_array($components)) {
                foreach ($components as $name) {
                    if (isset($obj->_data[$name])) unset($obj->_data[$name]);
                }
            } else {
                if (isset($obj->_data[$components])) unset($obj->_data[$components]);
            }
            $obj->setModified();
        } else {
            O::app()->cache->delete(sprintf(self::$_cacheName, $userId, $recId));
            if (isset(self::$_loaded["{$userId}:{$recId}"])) unset(self::$_loaded["{$userId}:{$recId}"]);
        }
        
    }

        /**
     * 
     * @param int $userId
     * @param int $recId
     * @return UserState Description
     */
    public static function &load($userId, $recId) {
        //$cacheName = "oprecx:UserState:userId={$userId}:recId={$recId}";
        $objName = "{$userId}:{$recId}";
        if (!isset(self::$_loaded[$objName])) {
            $obj = new UserState;
            $obj->_userId = $userId;
            $obj->_recId = $recId;
            if (false === ($data = O::app()->cache->get(sprintf(self::$_cacheName, $userId, $recId)))) {
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
            O::app()->onEndRequest = array($this, 'save');
            $this->_modified = true;
        }
    }
    
    public function save() {
        if ($this->_modified) {
            O::app()->cache->set(sprintf(self::$_cacheName, $this->_userId, $this->_recId), $this->_data, 60);
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
        return $this->_recId;
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
                    CDbCommandEx::create()
                        ->select('d.div_id, d.name AS div_name')
                        ->from(TableNames::DIVISION_CHOICE . ' dc')
                        ->join(TableNames::DIVISION . ' d', 
                                '$dc.div_id = $d.div_id AND $d.rec_id = :rec_id AND $d.enabled = 1')
                        ->order('dc.weight, d.weight, d.name')
                        ->where('$dc.user_id = :user_id')
                        ->queryAll(true, array('rec_id' => $this->_recId, 'user_id' => $this->_userId))
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
            $command = CDbCommandEx::create()
                    ->select('f.elm_id AS form_id, oe.name as form_name,  (COUNT(ff2.field_id) - COUNT(fv.value)) = 0 AS filled')
                    ->from(TableNames::FORM . ' f')
                    ->join(TableNames::REC_ELM . ' oe',
                            '$oe.elm_id = $f.elm_id AND $oe.rec_id = :rec_id')
                    ->join(TableNames::DIVISION_ELM . ' de',
                            '$de.elm_id = $oe.elm_id')
                    ->join(TableNames::DIVISION_CHOICE . ' dc',
                            '$dc.div_id = $de.div_id AND $dc.user_id = :user_id')
                    ->leftJoin(TableNames::FORM_FIELD . ' ff', 
                            '$ff.form_id = $f.elm_id AND $ff.required = 1')
                    ->leftJoin(TableNames::FORM_FIELD . ' ff2', 
                            '$ff2.form_id = $f.elm_id AND $ff2.required = 1')
                    ->leftJoin(TableNames::FORM_VALUE . ' fv', 
                            '$fv.field_id = $ff.field_id AND $fv.user_id = :user_id')
                    ->order('oe.weight, oe.name')
                    ->group('f.elm_id, oe.weight, oe.name');
            $this->_data['form'] = self::arrayToObject(
                    $command->queryAll(true, array('rec_id' => $this->_recId, 'user_id' => $this->_userId))
                );
            $this->setModified();
        }
        
        return $this->_data['form'];
    }
    
    public function getSelectedInterviewSlot() {
        if (!isset($this->_data['slots'])) {
            $this->_data['slots'] = CDbCommandEx::create()
                    ->selectDistinct('is.elm_id as slot_id, oe.name AS slot_name, ius.time, oe.weight')
                    ->from(TableNames::INTERVIEW_SLOT . ' is')
                    ->join(TableNames::REC_ELM . ' oe',
                            '$oe.elm_id = $is.elm_id AND $oe.rec_id = :rec_id')
                    ->join(TableNames::DIVISION_ELM . ' de',
                            '$de.elm_id = $oe.elm_id')
                    ->join(TableNames::DIVISION_CHOICE . ' dc',
                            '$dc.div_id = $de.div_id AND $dc.user_id = :user_id')
                    ->leftJoin(TableNames::INTERVIEW_USER_SLOT . ' ius', 
                            '$ius.slot_id = $is.elm_id AND $ius.user_id = :user_id')
                    ->order('oe.weight, oe.name')
                    //->where('dc.user_id = :user_id')
                    ->queryAll(true, array('rec_id' => $this->_recId, 'user_id' => $this->_userId));
            $this->setModified();
        }
        return self::arrayToObject($this->_data['slots']);
    }
}


?>
