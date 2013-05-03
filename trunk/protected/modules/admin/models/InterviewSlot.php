<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of InterviewSlot
 *
 * @author abie
 */
class InterviewSlot extends RecruitmentElement
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
                ->select()
                ->from(TableNames::INTERVIEW_SLOT . ' is')
                ->join(TableNames::REC_ELM . ' re', '$re.elm_id = $is.elm_id')
                ->where('$re.rec_id = :rec_id', array('rec_id' => $rec_id))
                ->order('re.weight, re.name');
    }
    
}

?>
