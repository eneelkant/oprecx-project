<?php

Yii::import('application.models._base.BaseUserMetas');

class UserMetas extends BaseUserMetas
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
}