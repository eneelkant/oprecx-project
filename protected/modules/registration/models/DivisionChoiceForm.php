<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DivisionChoiceForm
 *
 * @author abie
 * 
 * @property Divisions[] $allDivisions Divisions elem
 * @property string[] $allDivisionsName 
 * @property int $userId [write only] User ID
 */
class DivisionChoiceForm extends CFormModel {
    private $_allDivisions;
    private $_allDivisionsName;
    private $_org;
    
    /** @var string[] */
    public $choices;
    

    /**
     * 
     * @param Organizations $org
     * @param int $user_id
     */
    function __construct($scenario = '', $org = null, $divisions = null) {
        $this->_org = $org;
        $this->_allDivisions = $divisions;
        
        parent::__construct($scenario);
        
        $this->_allDivisionsName = array();
        /* @var $division Divisions */
        foreach ($this->_allDivisions as $division) {
            $this->_allDivisionsName[$division->div_id] = $division->name;
        }
        $this->choices = array();
        //$this->setUserId($user_id);
    }
    
    public function getAllDivisions() {
        return $this->_allDivisions;
    }
    
    public function getAllDivisionsName() {
        return $this->_allDivisionsName;
    }
    
    public function setUserId($userId) {
        if ($userId) {
            $command = Yii::app()->db->createCommand()
                        ->from('{{divisions}} d')->select('d.div_id')->from('{{division_choices}} dc')
                        ->join('{{divisions}} d', 'dc.div_id = d.div_id AND d.org_id = :org_id AND d.enabled = 1', array('org_id' => $this->_org->id))
                        ->order('dc.weight, d.weight, d.name')
                        ->where('dc.user_id = :user_id', array('user_id' => $userId));
            $this->choices = $command->queryColumn();
        } else {
            //$this->choices = array();
        }
    }
    
    public function rules(){
        return array (
            //array('choices', 'required'),
            array('choices', 'validateDivision'),
        );
    }
    
    public function validateDivision($attribute, $param){
        //$div = $this->divisions;
        $divs = array();
        foreach($this->choices as $div_id) {
            if ($div_id != ''){
                if (! isset($this->_allDivisionsName[$div_id])) {
                    $this->addError('choices', Yii::t('oprecx', 'Division :div_id not found.', array('div_id' => $div_id)));
                }
                $divs[] = $div_id;
            }
        }
        $div_count = count($divs);
        $this->choices = $divs;
        if ($div_count < 1 || $div_count > 3) {
            $this->addError('choices', 'Minimal 1 Max 3');
        }
    }
    
}

?>
