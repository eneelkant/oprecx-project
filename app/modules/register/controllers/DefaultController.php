<?php

class DefaultController extends RegisterController {

    public function actionIndex($org) {
        //$org = Organizations::model()->findByPk($org_id);
        $theOrg = Organizations::model()->find('name=:name', array('name' => $org));
        $this->render('index', array('org' => $theOrg));
    }
    
    public function actionDivision($org) {
        /** @var Organizations */
        $theOrg = Organizations::model()->with('divisions')->find('t.name=:name', array('name' => $org));
        $model = new DivisionChoiceForm($theOrg, 1);
        
        if (isset($_POST['DivisionChoiceForm'])){
            $model->attributes = $_POST['DivisionChoiceForm'];
            if ($model->validate()){
                // TODO: cek apakah pilihan divisi ada di organisasi, cek juga jumlah pilihan divisi

                $this->redirect(array('form', 'org' => $theOrg->name));
                
            }
        }
        $this->render('division', array('model' => $model, 'org' => $theOrg));
        
    }
    
    public function actionForm($org) {
        $theOrg = Organizations::model()->find('name=:name', array('name' => $org));
        /*
SELECT f.name as form_name, ff.* 
FROM `{{form_fields}}` ff, {{forms}} f, {{division_forms}} df, {{division_choices}} dc, {{divisions}} d
WHERE f.form_id = ff.form_id AND f.form_id = df.form_id AND df.div_id = dc.div_id AND dc.user_id = :user_id AND d.div_id = dc.div_id AND d.org_id = :org_id
GROUP BY ff.field_id
ORDER BY f.weight, f.name, ff.weight, ff.created';
         */
        $res = Yii::app()->db->createCommand()
                ->select('f.name as form_name, ff.*')
                ->from('{{form_fields}} ff, {{forms}} f, {{division_forms}} df, {{division_choices}} dc, {{divisions}} d')
                ->where('f.form_id = ff.form_id AND f.form_id = df.form_id AND df.div_id = dc.div_id AND 
                    d.div_id = dc.div_id AND dc.user_id=:user_id AND d.div_id = dc.div_id AND d.org_id=:org_id', 
                    array(':user_id' => 6, 'org_id' => $theOrg->id))
                ->group('ff.field_id') // takut dabel
                ->order('f.weight, f.name, ff.weight, ff.created')
                ->queryAll();
        
        //var_dump($res);
        
        
        //return;
        $this->render('form', array('org' => $theOrg, 'fields' => $res));
        
    }

}