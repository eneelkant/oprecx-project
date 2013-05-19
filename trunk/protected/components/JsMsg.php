<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of JsMsg
 *
 * @author abie
 */
class JsMsg extends CApplicationComponent
{
    private $_msg = array();
    
    public function init()
    {
        parent::init();
        
        O::app()->onEndRequest = array($this, 'insertScript');
    }
    
    public function set($name, $value) {
        $this->_msg[$name] = $value;
    }
    
    public function delete($name) {
        if (isset($this->_msg[$name]))
            unset($this->_msg[$name]);
    }

    public function insertScript($e) {
        O::app()->clientScript->registerScript('jsMsg', 'var msg=' . CJavaScript::encode($this->_msg) . ';', CClientScript::POS_BEGIN);
    }
}

?>
