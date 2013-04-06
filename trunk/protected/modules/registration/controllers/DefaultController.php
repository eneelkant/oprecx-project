<?php

class DefaultController extends RegisterController
{

    private function cekLogin()
    {
        if (Yii::app()->user->isGuest) {
            throw new CHttpException(403,
            Yii::t('oprecx', 'You must login to access this page. goto {link} to login',
                    array ('{link}' => $this->createAbsoluteUrl('index', array ('org' => $this->actionParams['org_name'])))));
        }
    }

    public function actionIndex()
    {
        //$org = Organizations::model()->findByPk($org_id);
        //$theOrg = Organizations::model()->cache(30)->find('name=:name', array('name' => $org));

        if (Yii::app()->user->isGuest) {
            $regForm   = new CForm(UserRegistrationForm::$formConfig, new UserRegistrationForm);
            $loginForm = new CForm(UserLoginForm::$formConfig, new UserLoginForm);

            $regForm->action['nexturl']   = $this->getURL('division');
            $loginForm->action['nexturl'] = $this->getURL('division');

            $this->render('index', array ('regForm'   => $regForm, 'loginForm' => $loginForm));
        }
        else {
            $this->render('index');
        }
    }

    public function actionDivision()
    {
        $this->cekLogin();
        $userId    = Yii::app()->user->id;
        $divisions = Divisions::model()->findAllByOrg($this->org->id);
        $model     = new DivisionChoiceForm($this->org);

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

                $this->redirect($this->getURL('form'));
                return;
            }
        }
        else {
            $model->userId = $userId;
        }
        $this->render('division', array ('model' => $model));
    }

    public function actionForm()
    {
        $this->cekLogin();
        /*
          SELECT f.name as form_name, ff.*
          FROM `{{form_fields}}` ff, {{forms}} f, {{division_forms}} df, {{division_choices}} dc, {{divisions}} d
          WHERE f.form_id = ff.form_id AND f.form_id = df.form_id AND df.div_id = dc.div_id AND dc.user_id = :user_id AND d.div_id = dc.div_id AND d.org_id = :org_id
          GROUP BY ff.field_id
          ORDER BY f.weight, f.name, ff.weight, ff.created';
         */


        $model = new RegistrationForm();
        $model->initComponent($this->org->id, Yii::app()->user->id);
        $form  = new CForm(array (
            'elements' => $model->elements,
            'buttons'  => array (
                'submit-form' => array ('type'  => 'submit', 'label' => 'Submit'),
            )
                ), $model
        );

        if ($form->submitted('submit-form') && $form->validate() && $model->save()) {
            $this->redirect($this->getURL('interview'));
        }

        $this->render('form', array ('form' => $form));
    }

    public function actionInterview()
    {
        $model = new InterviewSlotForm;
        $model->initTable($this->org->id, Yii::app()->user->id);
        $this->render('interview', array('model' => $model));
    }

}