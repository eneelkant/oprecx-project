<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ModuleUrlManager
 *
 * @author abie
 */
class ModuleUrlManager {

    static function collectRules() {
        if (!empty(O::app()->modules)) {
            $cache = O::app()->getCache();
            
            foreach (O::app()->modules as $moduleName => $config) {
                $urlRules = false;
                
                if($cache)
                    $urlRules = $cache->get('module.urls.'.$moduleName);
                
                if (false == $urlRules){
                    $module = O::app()->getModule($moduleName);
                    if (!empty($module->urlRules)) {
                        $urlRules = $module->urlRules;
                    }
                }
                
                if (false == $urlRules) {
                    O::app()->getUrlManager()->addRules($urlRules);
                }
            }
        }
        return true;
    }
}
?>
