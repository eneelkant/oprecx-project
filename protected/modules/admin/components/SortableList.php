<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DraggebleList
 *
 * @author abie
 */
class SortableList extends CWidget
{
    const ELM_CLASS = 'sortable';
    
    var $tagName = 'ul';
    var $htmlOptions = array();
    var $afterSort = NULL;
    var $id = NULL;
    var $class = array();
    var $handler = '.handler';
    var $selector = NULL;
    
    public function init()
    {
        if (! $this->id) {
            $this->id = 'sortable_' . md5(time());
        }
        $this->htmlOptions['id'] = $this->id;
        
        if (!is_array($this->class)) $this->class = array($this->class);
        $this->class[] = self::ELM_CLASS;
        $this->htmlOptions['class'] = implode(' ', $this->class);
        
        echo '<', $this->tagName, CHtml::renderAttributes($this->htmlOptions), '>';
    }

    public function run()
    {
        $clientScript = O::app()->getClientScript();
        
        $clientScript->registerScriptFile(O::app()->request->baseUrl . '/js/ui/jquery-ui' . (!YII_DEBUG ? '.min' : '') . '.js');
        $clientScript->registerScriptFile(O::app()->request->baseUrl . '/js/jquery.form' . (!YII_DEBUG ? '.min' : '') . '.js');
        
        if (! $this->selector) $this->selector = '#' . $this->id;
        
        $options = array();
        if ($this->afterSort) $options[] = 'update:' . $this->afterSort;
        if ($this->handler) $options[] = 'handle:"' . $this->handler . '"';
        $js = '$("' . $this->selector . '").sortable({' . implode(',', $options) . '});';
        
        $clientScript->registerScript('js-sort-' . $this->id, $js, CClientScript::POS_READY);        
        
        echo '</', $this->tagName, '>';
    }
}

?>
