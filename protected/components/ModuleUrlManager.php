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
        if (!empty(Yii::app()->modules)) {
            $cache = Yii::app()->getCache();
            
            foreach (Yii::app()->modules as $moduleName => $config) {
                $urlRules = false;
                
                if($cache)
                    $urlRules = $cache->get('module.urls.'.$moduleName);
                
                if (false == $urlRules){
                    $module = Yii::app()->getModule($moduleName);
                    if (!empty($module->urlRules)) {
                        $urlRules = $module->urlRules;
                    }
                }
                
                if (false == $urlRules) {
                    Yii::app()->getUrlManager()->addRules($urlRules);
                }
            }
        }
        return true;
    }
}
?>
