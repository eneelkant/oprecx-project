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
class RecruitmentForm extends RecruitmentElement
{
    protected $_attributeNames = array('id', 'name', 'divList');
    
    /**
     * 
     * @param string $class
     * @return RecruitmentForm
     */
    public static function model($class = __CLASS__)
    {
        return parent::model($class);
    }
    
    protected function getSqlCommandByRecId($rec_id)
    {
        return CDbCommandEx::create()
                ->select('f.elm_id as id, oe.name')
                ->from(TableNames::FORM . ' f')
                ->join(TableNames::REC_ELM . ' oe', '$oe.elm_id = $f.elm_id')
                ->where('$oe.rec_id = :rec_id', array('rec_id' => $rec_id))
                ->order('oe.weight, oe.created DESC');
    }
    
}

?>