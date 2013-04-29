<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SortForm
 *
 * @author abie
 */
class SortForm extends CWidget
{
    var $view = 'application.modules.admin.views.sort_form';
    var $viewOptions = array();
    
    // list
    var $listId;
    var $listTag = 'ul';
    var $listOptions = array();
    
    // list item
    var $listItemView;
    var $listItemData;
    var $listItemOptions = array();
    
    // FORM
    var $action;
    var $formId;    
    var $formOptions = array();
    
    // General info
    var $title;    
    var $backUrl = NULL;
    
    // button
    var $addButtonText;    
    var $saveButtonText;
    
    
    public function run()
    {
        $clientScript = O::app()->clientScript;
        $clientScript->registerScriptFile(O::app()->request->baseUrl . '/js/ui/jquery-ui' . (!YII_DEBUG ? '.min' : '') . '.js');
        $clientScript->registerScriptFile(O::app()->request->baseUrl . '/js/jquery.form' . (!YII_DEBUG ? '.min' : '') . '.js');
        
        $this->formOptions['id'] = $this->formId;
        $this->formOptions['action'] = $this->action;
        
        if (! isset($this->formOptions['htmlOptions'])) $this->formOptions['htmlOptions'] = array();
        if (! isset($this->formOptions['htmlOptions']['class'])) $this->formOptions['htmlOptions']['class'] = '';
        $this->formOptions['htmlOptions']['class'] .= 'oprecx-sort-form';
        
        if (! isset($this->listOptions['class'])) $this->listOptions['class'] = '';
        $this->listOptions['class'] .= 'oprecx-sort-list';
        $this->listOptions['id'] = $this->listId;
        
        if (! $this->addButtonText)
            $this->addButtonText = O::t ('oprecx', 'Add Item');
        if (! $this->saveButtonText)
            $this->saveButtonText = O::t ('oprecx', 'Save');
        $this->render($this->view, $this->viewOptions);
    }
    //put your code here
}

?>
