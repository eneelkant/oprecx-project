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
    
    public function actionNew() {
        $model = new NewSlotForm();
        
        if (isset($_POST['NewSlotForm'])) {
            $model->setAttributes($_POST['NewSlotForm']);
            
            if ($model->validate() && $model->submit($this->rec->id)) {
                $this->redirect(array('edit', 'id' => $model->getId()));
            }
            
        } else {
            $model->name = O::t('oprecx', 'Interview Slot');
            $model->timeRanges = array(
                '08:00:00', '12:00:00',
                '13:00:00', '15:00:00',
                '15:00:00', '18:00:00',
            );
            $model->dateRange = date('Y-m-d', time()) . ' - ' . date('Y-m-d', time() + 432000); // 432000 = 5 * 24 * 60 * 60
            $model->defaultMax = 1;
            $model->duration = 3600;
            $model->divList = array_keys($this->divList);
        }
        $this->render('new', array('model' => $model));
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
    
    public function actionSort() {
        if (isset($_POST['SlotList']) && isset($_POST['SlotList']['items'])) {
            $this->checkAccess('slot.sort');
            try {
                InterviewSlot::model()->sortOrDelete($this->rec->id, $_POST['SlotList']['items']);
                $error = NULL;
            } catch (CException $e) {
                $error = $e->getMessage();
            }
            
            if (O::app()->getRequest()->isAjaxRequest) {
                echo CJSON::encode(array(
                   'status' => ($error == NULL ? 'OK' : 'ERROR'),
                   //'data' => $items,
                   'error' => $error,
                ));
            } else {
                $url = array('index');
                if ($error) $url['error'] = $error;
                $this->redirect($url);
            }
        }
        else {
            throw new CHttpException(403);
        }
    }
}

?>
