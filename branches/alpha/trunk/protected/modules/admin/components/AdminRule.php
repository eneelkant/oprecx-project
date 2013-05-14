<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AdminRule
 * 
 * @property string $ruleName Description
 *
 * @author abie
 */
class AdminRule extends CComponent
{
    /*
    public static $access_rules = array(
        'add-division' => array('admin', 'editor'),
        'delete-division' => array('admin', 'editor'),
        'edit-division' => array('admin', 'editor'),
         
    );
     * *
     */
    
    const SUPER_ADMIN = 'super';
    const EDITOR = 'editor';
    const ANALYSIST = 'analysist';
    
    public static $access_rules = array(
        self::SUPER_ADMIN => '*',
        self::EDITOR => '*.edit|view',
        self::ANALYSIST => '*.view'
    );
    
    private $_rule;
    private $_recId, $_userId;
    
    public function __construct($recId, $userId)
    {
        $this->_recId = $recId;
        $this->_userId = $userId;
    }
    
    public function getRuleName() {
        if (!$this->_rule) {
            $this->_rule = CDbCommandEx::create()
                ->select('rule')
                ->from(TableNames::REC_ADMIN)
                ->where('$rec_id = :rec_id AND $user_id = :user_id', 
                        array('rec_id' => $this->_recId, 'user_id' => $this->_userId))
                ->limit(1)
                ->queryScalar();
        }
        
        return $this->_rule;
    }
    
    public function canI($action) {
        $ruleName = $this->getRuleName();
        if (! $ruleName || ! isset(self::$access_rules[$ruleName])) return false;
        
        $rules = self::$access_rules[$ruleName];
        $actionArr = explode('.', $action);
        if (is_string($rules)) 
            return self::checkRule ($rules, $actionArr);
        else {
            foreach ($rules as $rule) {
                if (self::checkRule($rule, $actionArr))
                        return true;
            }
            return false;
        }
    }
    
    private static function checkRule($rule, $actionArr) {
        foreach (explode('.', $rule) as $i => $v) {
            if ($v != '*' && isset($actionArr[$i]) && !in_array($actionArr[$i], explode('|', $v))) {
                return FALSE;
            }
        }
        return TRUE;
    }

    /**
    public function canI($action) {
        $ruleName = $this->getRuleName();
        if ($ruleName == self::SUPER_NAME) return true;
        return true;
    }
     * 
     */
}

?>
