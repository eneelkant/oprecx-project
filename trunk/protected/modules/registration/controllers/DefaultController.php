<?php

class DefaultController extends RegisterController
{
    public $isWizard = false;
    public $backAction = 'index';

    public function init()
    {
        parent::init();
        $params =& $this->getActionParams();
        $this->pageTitle = $this->rec->full_name . ' | ' . O::t('oprecx', 'Oprecx Registration');
        $this->isWizard = isset($params['wiz']) && $params['wiz'] == 1;
    }
    
    private function cekLogin($next)
    {
        if (O::app()->user->isGuest) {
            $url = $this->getURL('index', array('next' => $next));
            $this->redirect($url);
            /*throw new CHttpException(403,
            O::t('oprecx', 'You must login to access this page. goto {link} to login',
                    array ('{link}' => $url)));*/
        }
    }
    
    private function afterSave($next_action) {
        if ($this->isWizard) {
            $this->redirect($this->getURL($next_action, array('wiz' => 1)));
            return true;
        } elseif (isset($this->actionParams['edit']) && $this->actionParams['edit']) {
            $this->redirect($this->getURL('index'));
            return true;
        }
        return false;
    }

    public function actionIndex()
    {
        if (O::app()->user->isGuest) {
            if (isset($this->actionParams['next'])) $next = $this->getURL($this->actionParams['next']);
            else $next = $this->getURL('index', array('just_login' => 1));
            
            $regModel = new UserRegisterForm; $regForm   = $regModel->createForm();
            $loginModel = new UserLoginForm; $loginForm = $loginModel->createForm();
            $regForm->action['nexturl'] = $loginForm->action['nexturl'] = $next;

            $this->render('index', array ('regForm'   => $regForm, 'loginForm' => $loginForm));
            return;
        }
        elseif (isset($this->actionParams['just_login'])) {
            $div_count = O::app()->db->createCommand()
                    ->select('COUNT(dc.div_id)')
                    ->from(TableNames::DIVISION_CHOICE . ' dc')
                    ->join(TableNames::DIVISION . ' d', 'd.div_id = dc.div_id AND d.rec_id = :rec_id')
                    ->where('dc.user_id = :user_id')
                    ->limit(1)
                    ->queryScalar(array('rec_id' => $this->rec->id, 'user_id' => O::app()->user->id));
            
            if ($div_count == 0){
                $this->redirect($this->getURL('division', array('wiz' => 1)));
                return;
            }
            //$this->render('index');
        }
        //else {
            $this->render('index');
        //}
    }

    public function actionDivision()
    {
        $divisions = Division::model()->findAllByRecId($this->rec->id);
        
        if (! O::app()->user->isGuest) {
            $userId    = O::app()->user->id;
            $model     = new DivisionChoiceForm('', $this->rec, $divisions);

            if (isset($_POST['DivisionChoiceForm'])) {
                $model->attributes = $_POST['DivisionChoiceForm'];
                if ($model->validate() && $model->save($userId)) {
                    
                    //if ($this->isWizard) $this->redirect($this->getURL('form', array('wiz' => 1)));
                    if ($this->afterSave('form')) return;
                    //return;
                }
            }
            else {
                $model->setUserId($userId);
            }
            $this->render('division', array ('divisions' => $divisions, 'model' => $model));
        }
        else {
            $this->render('division', array ('divisions' => $divisions));
        }
    }

    public function actionForm()
    {
        $this->cekLogin('form');
        if ($this->isWizard) $this->backAction = 'division';
        
        $model = new RegistrationForm();
        $model->initComponent($this->rec->id, O::app()->user->id);
        $form  = new CForm(
                array (
                    'elements' => $model->elements,
                    'buttons'  => array (
                        'submit-form' => array ('type'  => 'submit', 'label' => O::t('oprecx', 'Save')),
                    ),
                    'activeForm' => array(
                        'class' => 'CActiveForm', 
                        /*'enableClientValidation' => true,
                        'clientOptions' => array(
                            'validateOnSubmit' => true,
                        ),*/
                        
                    ),
                    
                    'attributes' => array('id' => 'reg-form'),
                ),
                $model
        );
        

        if ($form->submitted('save') && $form->validate() && $model->save()) {
            //if ($this->isWizard) $this->redirect($this->getURL('interview', array('wiz' => 1)));
            if ($this->afterSave('interview')) return;
        }

        $this->render('form', array ('form' => $form));
    }

    public function actionInterview()
    {
        $this->cekLogin('interview');
        $model = new InterviewSlotForm('', $this->rec->id, O::app()->user->id);
        if ($this->isWizard) $this->backAction = 'form';
        
        if (isset($_POST['InterviewSlotForm'])) {
            $model->setAttributes($_POST['InterviewSlotForm']);
            if ($model->validate() && $model->save()) {
                if ($this->afterSave('finish')) return;
            }
        }
        $this->render('interview', array('model' => $model));
    }

}