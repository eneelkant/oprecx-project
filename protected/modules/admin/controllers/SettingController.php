<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GeneralSettingController
 *
 * @author abie
 */
class SettingController extends AdminController
{
    protected function beforeAction($action)
    {
        if ($this->getOrg() == NULL) {
            throw new CHttpException('404');
        }
        $this->layout = 'setting';
        return parent::beforeAction($action);
    }
    
    public function actionInfo()
    {
        $model = new OrganizationsEx();
        
        $model->attributes = $this->org->attributes;
        if (isset($_POST['OrganizationsEx'])) {
            $model->setAttributes($_POST['OrganizationsEx'], false);
            
            
            $model->setIsNewRecord(false);
            if ($model->save()) {
                $this->_org->invalidateCache();
                $this->_org = FALSE;
            }
        }
        //$model = OrganizationsForm::model('OrganizationsForm')->findByPk(1);  // new OrganizationsForm($this->getOrg());
        //echo 'aaa';
        $this->render('info', array('model' => $model));
    }
    
    public function actionDivision() {
        $this->render('division');
    }
    
    public function actionSaveDivisionList() {
        if (isset($_POST['DivisionList']) && isset($_POST['DivisionList']['items'])) {
            $items = $_POST['DivisionList']['items'];
            //sleep(3);
            // TODO check before save
            $db = O::app()->getDb();
            $transaction = $db->beginTransaction();
            $error = NULL;
            try {
                foreach ($items as $weight => $div_id) {
                    $db->createCommand()->update(TableNames::DIVISIONS, array(
                            'weight' => $weight,
                             //'updated' => new CDbExpression('CURRENT_TIMESTAMP'),
                            ), 
                            'div_id = :div_id AND org_id = :org_id', // security check
                            array('div_id' => $div_id, 'org_id' => $this->org->id)
                        );
                }
                
                $db->createCommand()->delete(TableNames::DIVISIONS, 
                        array('AND', 'org_id = :org_id', array('NOT IN', 'div_id', array_values($items))), 
                        array('org_id' => $this->org->id));
                
                $transaction->commit();
            }
            catch (Exception $e) {
                $error = 'SQL ERROR: ' . $e->getMessage();
                $transaction->rollback();
            }
            
            if (O::app()->getRequest()->isAjaxRequest) {
                echo CJSON::encode(array(
                   'status' => ($error == NULL ? 'OK' : 'ERROR'),
                   'data' => $items,
                   'error' => $error,
                ));
            } else {
                $url = array('division');
                if ($error) $url['error'] = $error;
                $this->redirect($url);
            }
        }
        else {
            throw new CHttpException(403);
        }
    }
    
    public function actionSaveDivision() {
        if (isset($_POST['Division'])) {
            $db = O::app()->getDb();
                
            if ($_POST['Division']['div_id'] == 0) {
                $row = CDbCommandEx::create($db)
                        ->select('MAX(t1.div_id) as i, MAX(t2.weight) as m, COUNT(t2.weight) c ')
                        ->from(TableNames::DIVISIONS . ' t1')
                        ->leftJoin(TableNames::DIVISIONS . ' t2', '$t2.org_id = :org_id', array('org_id' => $this->org->id))
                        ->queryRow();
                $div_id = $row['i'] + 1;

                $db->createCommand()->insert(TableNames::DIVISIONS, array(
                    'div_id' => $div_id,
                    'org_id' => $this->org->id,
                    'name' => $_POST['Division']['name'],
                    'description' => $_POST['Division']['description'],
                    'weight' => max($row['m'], $row['c']) + 1,
                ));

            }
            else {
                $div_id = $_POST['Division']['div_id'];

                $db->createCommand()->update(TableNames::DIVISIONS, array(
                    'name' => $_POST['Division']['name'],
                    'description' => $_POST['Division']['description'],
                    //'updated' => new CDbExpression('CURRENT_TIMESTAMP'),
                ), 'div_id = :div_id', array('div_id' => $div_id));
            }
                
                
            if (O::app()->getRequest()->isAjaxRequest) {                
                $division = Divisions::model()->findByPk($div_id);
                echo CJSON::encode(array(
                   'status' => 'OK',
                   'div_id' => $div_id,
                   //'data' => $item,
                   'html' => $this->renderPartial('_division_item', array('item' => $division), true),
                ));
            } else {
                $this->redirect(array('division'));
            }
        }
        else {
            throw new CHttpException(403);
        }
    }
}

?>
