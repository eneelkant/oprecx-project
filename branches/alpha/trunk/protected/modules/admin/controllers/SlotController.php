<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SlotController
 *
 * @author abie
 */
class SlotController extends AdminController
{
    public $layout = 'setting';
    
    public function filters()
    {
        return array (
            'ajaxOnly + slotOptions',
            'postOnly + slotOptions',
        );
    }
    
    public function actionIndex(){
        $this->render('index', array('slots' => InterviewSlot::model()->findAllByRecId($this->rec->id)));
    }
    
    public function actionEdit($id)
    {
        $this->render('edit', array('slot' => InterviewSlot::model()->findById($id)));
    }
    
    public function actionSaveOptions($id) {
        
        if (isset($_POST['slot_options'])) {
            // check id
            $org_id = CDbCommandEx::create()
                    ->select('re.rec_id')
                    ->from(TableNames::INTERVIEW_SLOT . ' is')
                    ->join(TableNames::REC_ELM_as('re'), '$re.elm_id = $is.elm_id')
                    ->limit(1)
                    ->where('$is.elm_id = :elm_id', array('elm_id' => $id))
                    ->queryScalar();
            
            if ($org_id == $this->rec->id) {
                CDbCommandEx::create()->update(
                        TableNames::INTERVIEW_SLOT, 
                        array('options' => serialize($_POST['slot_options'])),
                        'elm_id = :elm_id',
                        array('elm_id' => $id)
                );
                echo json_encode(array('status' => 'OK', 'error' => null));
            }
            else {
                echo json_encode(array('status' => 'ERROR', 'error' => '403'));
            }
        }
        
    }
}

?>
