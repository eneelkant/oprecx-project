<?php

Yii::import('application.models._base.BaseUsers');

class Users extends BaseUsers
{

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @param int $pk
     * @param string|array $condition
     * @param array $params
     * @return type
     */
    public function findByPk($pk, $condition = '', $params = array ())
    {
        $cache   = Yii::app()->cache;
        $cacheId = 'oprecx:User:id=' . $pk;
        if (($obj     = $cache->get($cacheId)) === false) {
            $obj    = $this->findByAttributes(array ('id' => $pk), $condition, $params);
            $depend = null; //new CDbCacheDependency('SELECT updated FROM {{users}} WHERE id=' . $pk . ' LIMIT 1');
            //$depend->params = array(':user_id', $pk);
            $cache->set($cacheId, $obj, 3600, $depend);
        }
        return $obj;
    }

    public function defaultScope()
    {
        return array (
            'select' => 'id, email, full_name, img_id',
        );
    }

}