<?php
/** @var string $submit_name */
if (!isset($submit_name))
    $submit_name = 'submit';

$grid = JqmGrid::createGrid('fieldset');
$grid->addColumn(JqmTag::buttonLink(Yii::t('oprecx', 'Back'), $this->getURL('index'))->icon('back')->iconPos('left'));
$grid->addColumn(JqmTag::jSubmit(Yii::t('oprecx', 'Back'), $submit_name)->icon('check')->theme('b')->iconPos('right'));
$grid->render(true);

?>
