<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * @property int $id Description
 * @property string $name Description
 * @property int[] $divList Description
 */
class OrgForm extends OrgElms
{
    protected $_attributeNames = array('id', 'name', 'divList');
    
    /**
     * 
     * @param string $class
     * @return OrgForm
     */
    public static function model($class = __CLASS__)
    {
        return parent::model($class);
    }
    
    /**
     * 
     * @param type $orgId
     * @return OrgForm[]
     */
    function findAllByOrg($orgId)
    {
        $reader = CDbCommandEx::create()
                ->select('f.elm_id as id, oe.name')
                ->from(TableNames::FORMS . ' f')
                ->join(TableNames::ORG_ELMS . ' oe', '$oe.elm_id = $f.elm_id')
                ->where('$oe.org_id = :org_id', array('org_id' => $orgId))
                ->order('oe.weight, oe.created DESC')
                ->query();
        $results = array();
        
        return $this->populate($reader);        
    }
    
}

?>
