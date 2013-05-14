<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 */
class RecruitmentForm extends RecruitmentElement
{
    //protected $_attributeNames = array('id', 'name', 'divList');
    protected $_tableName = TableNames::FORM;
    
    /**
     * 
     * @param string $class
     * @return RecruitmentForm
     */
    public static function model($class = __CLASS__)
    {
        return parent::model($class);
    }
    
}

?>
