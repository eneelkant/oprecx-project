<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CFormModelEx
 *
 * @author abie
 */
class CFormModelEx extends CFormModelEx {
    private $_fieldOptions;
    
    public function fieldOptions() {
        return array();
    }
    
    public function renderInput($name, $htmlOptions = array()) {
        if (!isset($this->_fieldOptions)) {
            $this->_fieldOptions = $this->fieldOptions();
        }
        $options = $this->_fieldOptions;
        if (array_key_exists($name, $options)) {
            if (is_array($options[$name]))
                return $this->_render($name, $options, $htmlOptions);
            else 
                return $this->_render($name, array($options), $htmlOptions);
        } else {
            return $this->_render($name, array('text'), $htmlOptions);
        }
    }
    
    private function _render($name, $options, $htmlOptions) {
        foreach ($options as $k => $v) {
            if ($k != 0 && strncasecmp($k, 'on', 2) != 0) {
                $htmlOptions[$k] = $v;
            }
        }
        
        switch (array_shift($options)) {
            case 'password':
                return CHtml::activePasswordField($this, $name, $htmlOptions);
                
            default :
                return Chtml::activeTextField($this, $name, $htmlOptions);
        }
    }
}

?>
