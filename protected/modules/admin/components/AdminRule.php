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
    public static $access_rules = array(
        'add-division' => array('admin', 'editor'),
        'delete-division' => array('admin', 'editor'),
        'edit-division' => array('admin', 'editor'),
         
    );
    
    const SUPER_NAME = 'super';
    
    private $_rule;
    private $_orgId, $_userId;
    
    public function __construct($orgId, $userId)
    {
        $this->_orgId = $orgId;
        $this->_userId = $userId;
    }
    
    public function getRuleName() {
        if (!$this->_rule) {
            $this->_rule = CDbCommandEx::create()
                ->select('rule')
                ->from(TableNames::ORG_ADMINS)
                ->where('$org_id = :org_id AND $user_id = :user_id', 
                        array('org_id' => $this->_orgId, 'user_id' => $this->_userId))
                ->limit(1)
                ->queryScalar();
        }
        
        return $this->_rule;
    }

    public function canI($action) {
        $ruleName = $this->getRuleName();
        if ($ruleName == self::SUPER_NAME) return true;
        return true;
    }
}

?>
