<?php
/**
 * This is the configuration for generating message translations
 * for the Yii framework. It is used by the 'yiic message' command.
 */
return array(
	'sourcePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'messagePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'messages',
	'languages'=>array('id'),
	'fileTypes'=>array('php'),
	'translator' => 'O::t',
	//'overwrite'=>true,
	'exclude'=>array(
		'.svn',
		'.gitignore',
		'.git',
		'/data',
		'/messages',
		'assets',
	),
);
