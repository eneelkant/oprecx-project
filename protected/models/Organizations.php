<?php

Yii::import('application.models._base.BaseOrganizations');

class Organizations extends BaseOrganizations
{

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public static function getByName($name)
    {
        //return self::model()->findByAttributes(array ('name' => $name));
        
        /** @var CCache $cache */
        $cache   = Yii::app()->cache;
        $cacheId = 'oprecx:Organization:name=' . $name;
        if (($obj     = $cache->get($cacheId)) == false) {
            $obj            = self::model()->findByAttributes(array ('name' => $name));
            $depend         = new CDbCacheDependency('SELECT updated FROM {{organizations}} WHERE name=:name');
            $depend->params = array ('name' => $name);
            $cache->set($cacheId, $obj, 60, null);
        }
        return $obj;
    }

}