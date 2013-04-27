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
class DraggebleList extends CWidget
{
    var $tagName = 'ul';
    var $htmlOptions = array();
    
    public function init()
    {
        if (! isset($this->htmlOptions['class'])) $this->htmlOptions['class'] = '';
        $this->htmlOptions['class'] .= ' draggable-list';
        
        echo '<', $this->tagName, CHtml::renderAttributes($this->htmlOptions), '>';
    }

    public function run()
    {
        return '</' . $this->tagName . '>';
    }
}

?>
