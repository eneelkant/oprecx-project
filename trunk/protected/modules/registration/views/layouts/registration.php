<?php
/* @var $this RegisterController */
$user = O::app()->user;

if ($user->isGuest) {
    $this->metaData['type'] = 'recruitment';
    $this->metaData['name'] = $this->metaData['og:title'] = $this->metaData['twitter:title'] = $this->rec->full_name;
    $this->metaData['description'] = substr(strip_tags($this->rec->description), 0, 300);
} else {
    $this->metaData = null;
}

?>
<?php $this->beginContent(); ?>

<div data-role="header" class="head registration">
    <div class="wrapper">
        <h1 class="org-name">
            <!-- a href="#main-panel" data-role="button" data-inline="true" data-iconpos="notext" data-icon="arrow-r" 
               class="ui-icon-alt" data-theme="c" data-mini="true" id="panel-show-button">Menu</a -->
            
            <?php echo CHtml::link($this->rec->full_name, $this->getURL('index')); ?>
        </h1>
        <div class="user-name">
            <?php
            if (!$user->isGuest) {
                ;
                echo '<span>', CHtml::encode($user->getState('fullname')), '</span> | ';
                echo CHtml::link(O::t('oprecx', 'Log Out'), array ('/user/logout'), array('rel' => 'external'));
            }
            else {

                echo O::t('oprecx', 'Hi, there! {login} or {reg}?.',
                        array (
                            '{reg}'   => CHtml::link(O::t('oprecx', 'register'), array('/user/register', 'nexturl' => $_SERVER['REQUEST_URI'])),
                            '{login}' => CHtml::link(O::t('oprecx', 'login'), array('/user/login', 'nexturl' => $_SERVER['REQUEST_URI'])),
                        )
                );
            }
            ?>
        </div>

    </div>
    <div class="clear"></div>
</div><!-- /header -->

<div data-role="content" class="wrapper" style="clear: both">
<?php echo $content; ?>
</div><!-- /content -->

<?php /* ?>
<div data-role="panel" id="main-panel" data-position="left" data-display="overlay" data-theme="a" class="ui-panel ui-panel-closed">
    <ul data-role="listview" data-inset="false">
        <li><?php echo CHtml::link(O::t('oprecx',
                'Summary'), $this->getURL('index')); ?></li>

        <?php if (!$user->isGuest) : ?>
            <li data-role="divider" data-theme="a"><?php echo O::t('oprecx',
                'Registration') ?></li>
            <li><?php echo CHtml::link(O::t('oprecx', 'Form'),
                $this->getURL('form')); ?></li>
            <li><?php echo CHtml::link(O::t('oprecx', 'Interview Slot'),
                $this->getURL('interview')); ?></li>
            <li><?php echo CHtml::link(O::t('oprecx', 'Division Choices'), 
                $this->getURL('division')); ?></li>
            <li data-role="divider" data-theme="a">Account</li>
            <li><?php echo CHtml::link(O::t('oprecx',
                        'Log Out'), array ('/user/logout')); ?></li>

            <?php else: ?>

            <?php endif ?>

        <li data-role="divider" data-theme="a"><?php echo O::t('oprecx',
                    'Oprecx') ?></li>
        <li data-icon="home"><?php
            echo CHtml::link(O::t('oprecx', 'Home'), array ('/site/index'),
                    array ('data-icon' => 'home'));
            ?></li>
        <li><?php
            echo CHtml::link(O::t('oprecx', 'About'), array ('/site/page', 'view' => 'oprecx'));
            ?></li>
        <li data-theme="e" data-icon="back">
            <?php echo CHtml::link(O::t('oprecx', 'Cancel'), '#', array('data-rel'=>'close')); ?>
        </li>
    </ul>
</div><!-- /panel -->
<?php // */ ?>
<?php $this->endContent(); ?>