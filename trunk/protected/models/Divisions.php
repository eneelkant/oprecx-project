<?php

Yii::import('application.models._base.BaseDivisions');

class Divisions extends BaseDivisions
{

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function findAllByOrg($orgId, $conditions = '', $params = array ())
    {
        $model = self::model();
        if (!is_array($conditions)) {
            $conditions = array (
                'condition' => $conditions,
                'params'    => $params,
            );
        }
        if (!isset($conditions['order'])) $conditions['order'] = 'weight, name';
        return $model->findAllByAttributes(array ('org_id' => $orgId), $conditions);
    }

}