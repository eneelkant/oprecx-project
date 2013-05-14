<?php
/** @var string $submit_name */
/** @var RegisterController $this */
if (!isset($submit_name))
    $submit_name = 'save';

$grid = JqmGrid::createGrid('fieldset');
$back_link_args = $this->isWizard ? array('wiz' => 1) : array() ;
$grid->addColumn(JqmTag::buttonLink(O::t('oprecx', 'Back'), $this->getURL($this->backAction, $back_link_args))->icon('back')->iconPos('left'));
$grid->addColumn(JqmTag::jSubmit($this->isWizard ? O::t('oprecx', 'Next') : O::t('oprecx', 'Save'), $submit_name)
        ->icon('check')->theme('b')->iconPos('right'));
$grid->render(true);

?>
