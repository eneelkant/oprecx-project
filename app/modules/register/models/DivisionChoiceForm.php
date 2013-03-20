<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DivisionChoiceForm
 *
 * @author abie
 */
class DivisionChoiceForm extends CFormModel {
    public $divisions;

    /**
     * 
     * @param Organizations $org
     * @param int $user_id
     */
    function __construct($org, $user_id) {
        
    }
    
    public function rules(){
        return array (
            array('divisions', 'required'),
            array('divisions', 'validateDivision'),
        );
    }
    
    public function validateDivision($attribute, $param){
        //$div = $this->divisions;
        $divs = array();
        foreach($this->divisions as $div) {
            if ($div != '')
                $divs[] = $div;
        }
        $div_count = count($divs);
        $this->divisions = $divs;
        if ($div_count < 1 || $div_count > 3) {
            $this->addError('divisions', 'Pilihan divisi ...');
        }
    }
}

?>
