<?php

O::import('application.models._base.BaseImages');

class Images extends BaseImages
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
}