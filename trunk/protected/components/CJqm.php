<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CJqm
 *
 * @author abie
 */
class CJqm extends CHtml {
    public static function jqmLink($label, $href, $data = array(), $htmlOptions = array()) {
        return parent::link($label, $href, self::_merge_options($data, $htmlOptions, 'button'));
    }
    
    
    
    private static function _merge_options($data, $opt, $role = null, $default_data = null) {
        //$htmlOpt = array();
        
        if (!is_array($opt)) {
            $opt = array();
        }
        
        if ($role) {
            $opt['data-role'] = $role;
        }
        if ($default_data) {
            foreach($default_data as $k => $v) {
                $opt['data-' . $k] = $v === true ? 'true' : $v;
            }
        }
        
        foreach($data as $k => $v) {
            $opt['data-' . $k] = $v === true ? 'true' : $v;
        }
        
        return $opt;
    }
}

?>
