<?php

class DefaultController extends RegisterController {

    private function cekLogin() {
        if (Yii::app()->user->isGuest) {
            throw new CHttpException(403, 
                    Yii::t('oprecx', 'You must login to access this page. goto {link} to login', 
                            array('{link}' => $this->createAbsoluteUrl('index', array('org' => $this->actionParams['org'])))));
        }
    }

    public function actionIndex($org) {
        //$org = Organizations::model()->findByPk($org_id);
        //$theOrg = Organizations::model()->cache(30)->find('name=:name', array('name' => $org));

        if (Yii::app()->user->isGuest) {
            $regForm = new CForm(UserRegistrationForm::$formConfig, new UserRegistrationForm);
            $loginForm = new CForm(UserLoginForm::$formConfig, new UserLoginForm);
            
            $regForm->action['nexturl'] = $this->getURL('division');
            $loginForm->action['nexturl'] = $this->getURL('division');
            

            if (
                    ($regForm->submitted('register') && $regForm->validate()) || 
                    ($loginForm->submitted('login') && $loginForm->validate() && $loginModel->login())
            ) {
                $this->redirect(array('division', 'org' => $org));
                //return;
            } else {
                $this->render('index', array('regForm' => $regForm, 'loginForm' => $loginForm));
            }
        } else {
            $this->render('index');
        }        
    }

    public function actionDivision($org) {
        $this->cekLogin();
        
        /** @var Organizations */
        $model = new DivisionChoiceForm($this->org, 1);

        if (isset($_POST['DivisionChoiceForm'])) {
            $model->attributes = $_POST['DivisionChoiceForm'];
            if ($model->validate()) {
                // TODO: cek apakah pilihan divisi ada di organisasi, cek juga jumlah pilihan divisi

                $this->redirect($this->getURL('form'));
            }
        }
        $this->render('division', array('model' => $model));
    }

    public function actionForm($org) {
        $this->cekLogin();
        /*
          SELECT f.name as form_name, ff.*
          FROM `{{form_fields}}` ff, {{forms}} f, {{division_forms}} df, {{division_choices}} dc, {{divisions}} d
          WHERE f.form_id = ff.form_id AND f.form_id = df.form_id AND df.div_id = dc.div_id AND dc.user_id = :user_id AND d.div_id = dc.div_id AND d.org_id = :org_id
          GROUP BY ff.field_id
          ORDER BY f.weight, f.name, ff.weight, ff.created';
         */
        $res = Yii::app()->db->cache(100)->createCommand()
                ->select('f.name as form_name, ff.*')
                ->from('{{form_fields}} ff, {{forms}} f, {{division_forms}} df, {{division_choices}} dc, {{divisions}} d')
                ->where('f.form_id = ff.form_id AND f.form_id = df.form_id AND df.div_id = dc.div_id AND 
                    d.div_id = dc.div_id AND dc.user_id=:user_id AND d.div_id = dc.div_id AND d.org_id=:org_id', array(':user_id' => 6, 'org_id' => $theOrg->id))
                ->group('ff.field_id') // takut dabel
                ->order('f.weight, f.name, ff.weight, ff.created')
                ->queryAll();

        $this->render('form', array('fields' => $res));
    }

}