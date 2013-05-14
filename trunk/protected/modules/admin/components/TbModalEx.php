<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

Yii::import('bootstrap.widgets.TbModal', false);

/**
 * Description of TbModalEx
 *
 * @author abie
 */
class TbModalEx extends TbModal
{
    public $formAction = NULL;
    public $modalTitle = '';
    public $buttons = null;


    public function init()
    {
        parent::init();
        echo '<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3>', 
            $this->modalTitle , '</h3></div><div class="modal-body">';
    }
    
    public function run()
    {
        if ($this->buttons == null) {
            $this->buttons = array(
                array(
                    'type'=>'primary',
                    'label'=> O::t('oprecx', 'OK'),
                    'url'=>'#',
                    'htmlOptions'=>array('data-dismiss'=>'modal'),
                ),
                array(
                    'label'=> O::t('oprecx', 'Cancel'),
                    'url'=>'#',
                    'htmlOptions'=>array('data-dismiss'=>'modal'),
                ),
            );
        }
        echo '</div><div class="modal-footer">';
        foreach ($this->buttons as $btn) {
            $this->controller->widget('bootstrap.widgets.TbButton', $btn);
        }
               
        echo '</div>';
        parent::run();
    }
}

?>