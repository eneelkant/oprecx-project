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

    public $min_choice, $max_choice;
    


    /**
     * 
     * @param Organizations $org
     * @param int $user_id
     */
    function __construct($scenario = '', $org = null, $divisions = null) {
        $this->_org = $org;
        $this->_allDivisions = $divisions;
        $this->min_choice = 1;
        $this->max_choice = 3;
        
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
            $command = CDbCommandEx::create()
                        ->select('d.div_id')
                        ->from('{{division_choices}} dc')
                        ->join('{{divisions}} d', 
                                '$dc.div_id = $d.div_id AND $d.org_id = :org_id AND $d.enabled = 1')
                        ->order('dc.weight, d.weight, d.name')
                        ->where('$dc.user_id = :user_id');
            $this->choices = $command->queryColumn(array('org_id' => $this->_org->id, 'user_id' => $userId));
        } else {
            //$this->choices = array();
        }
    }
    
    public function save($userId) {
        
        $db = Yii::app()->getDb();
        $transaction = $db->beginTransaction();
        try {
            $db->createCommand()->delete(TableNames::DIVISION_CHOICES, 
                    array('AND', 'user_id=:user_id', array('IN', 'div_id', array_keys($this->_allDivisionsName))),
                    array('user_id' => $userId));
            
            foreach ($this->choices as $weight => $div_id) {
                $db->createCommand()->insert(TableNames::DIVISION_CHOICES, array(
                    'user_id' => $userId,
                    'div_id' => $div_id,
                    'weight' => $weight
                ));
            }
            $transaction->commit();            
            UserState::invalidate($userId, $this->_org->id);
            return true;
        }
        catch (CDbException $e) {
            $transaction->rollback();
            return false;
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
                    $this->addError('choices', Yii::t('oprecx', 'Division with id "{div_id}" not found.', array('{div_id}' => $div_id)));
                }
                $divs[$div_id] = 1;
            }
        }
        $divs = array_keys($divs);
        $div_count = count($divs);
        $this->choices = $divs;
        if ($div_count < $this->min_choice || $div_count > $this->max_choice) {
            $this->addError('choices', Yii::t('oprecx', 'You must select at least {min} and at most {max}.',array(
                    '{min}' => Yii::t('oprecx', '1#a division|>1#{n} divisions', $this->min_choice),
                    '{max}' => Yii::t('oprecx', '1#a division|>1#{n} divisions', $this->max_choice),
                )));
        }
    }
    
}

?>
