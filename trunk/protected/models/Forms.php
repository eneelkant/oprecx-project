<?php

Yii::import('application.models._base.BaseForms');

class Forms extends BaseForms
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
}