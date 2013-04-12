<?php

class DefaultController extends RegisterController
{

    public function init()
    {
        parent::init();
        $this->pageTitle = $this->org->full_name . ' | ' . Yii::t('oprecx', 'Oprecx Registration');
    }
    
    private function cekLogin($next)
    {
        if (Yii::app()->user->isGuest) {
            $url = $this->getURL('index', array('next' => $next));
            $this->redirect($url);
            /*throw new CHttpException(403,
            Yii::t('oprecx', 'You must login to access this page. goto {link} to login',
                    array ('{link}' => $url)));*/
        }
    }

    public function actionIndex()
    {
        //$org = Organizations::model()->findByPk($org_id);
        //$theOrg = Organizations::model()->cache(30)->find('name=:name', array('name' => $org));

        if (Yii::app()->user->isGuest) {
            if (isset($this->actionParams['next'])) $next = $this->getURL($this->actionParams['next']);
            else $next = $this->getURL('index', array('just_login' => 1));
            
            $regModel = new UserRegisterForm; $regForm   = $regModel->createForm();
            $loginModel = new UserLoginForm; $loginForm = $loginModel->createForm();
            $regForm->action['nexturl'] = $loginForm->action['nexturl'] = $next;

            $this->render('index', array ('regForm'   => $regForm, 'loginForm' => $loginForm));
        }
        elseif (isset($this->actionParams['just_login'])) {
            // TODO : CEK apakah user udah pilih divisi, jika ya redirect ke divisi kalo ga munculin info user terkait
            // organisasi ini
             $this->redirect($this->getURL('division'));
            
            //$this->render('index');
        }
        else {
            $this->render('index');
        }
    }

    public function actionDivision()
    {
        $divisions = Divisions::model()->cache(60)->findAllByOrg($this->org->id);
        //$this->cekLogin('division');
        
        if (! Yii::app()->user->isGuest) {
            $userId    = Yii::app()->user->id;
            $model     = new DivisionChoiceForm('', $this->org, $divisions);

            if (isset($_POST['DivisionChoiceForm'])) {
                $model->attributes = $_POST['DivisionChoiceForm'];
                if ($model->validate()) {
                    foreach ($model->choices as $weight => $div_id) {
                        try {
                            Yii::app()->db->createCommand()->update('{{division_choices}}',
                                    array ('weight'  => $weight, 'choosed' => new CDbExpression('CURRENT_TIMESTAMP')),
                                    'div_id = :div_id AND user_id = :user_id',
                                    array ('div_id'  => $div_id, 'user_id' => $userId)
                            );
                        }
                        catch (Exception $e) {

                        }

                        try {
                            Yii::app()->db->createCommand()->insert('{{division_choices}}',
                                    array ('div_id'  => $div_id, 'user_id' => $userId, 'weight'  => $weight));
                        }
                        catch (Exception $e) {

                        }
                    }

                    //$this->redirect($this->getURL('form'));
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
        
        $model = new RegistrationForm();
        $model->initComponent($this->org->id, Yii::app()->user->id);
        $form  = new CForm(
                array (
                    'elements' => $model->elements,
                    'buttons'  => array (
                        'submit-form' => array ('type'  => 'submit', 'label' => 'Submit'),
                    ),
                    'activeForm' => array(
                        'class' => 'CActiveForm', 
                        'enableClientValidation' => true,
                        'clientOptions' => array(
                            'validateOnSubmit' => true,
                        ),
                    ),
                ),
                $model
        );

        if ($form->submitted('submit-form') && $form->validate() && $model->save()) {
            $this->redirect($this->getURL('interview'));
        }

        $this->render('form', array ('form' => $form));
    }

    public function actionInterview()
    {
        $this->cekLogin('interview');
        $model = new InterviewSlotForm('', $this->org->id, Yii::app()->user->id);
        
        if (isset($_POST['InterviewSlotForm'])) {
            $model->setAttributes($_POST['InterviewSlotForm']);
            if ($model->validate() && $model->save()) {
                echo 'aa';
                return;
            }
        }
        $this->render('interview', array('model' => $model));
    }

}