<?php
/* @var $this RegisterController */
$user = Yii::app()->user;
?>
<?php $this->beginContent(); ?>
<div data-role="panel" id="main-panel" data-position="left" data-display="overlay" data-theme="a">
    <ul data-role="listview" data-inset="false">
        <li><?php echo CHtml::link(Yii::t('oprecx',
                'Summary'), $this->getURL('index')); ?></li>

        <?php if (!$user->isGuest) : ?>
            <li data-role="divider" data-theme="a"><?php echo Yii::t('oprecx',
                'Registration') ?></li>
            <li><?php echo CHtml::link(Yii::t('oprecx', 'Form'),
                $this->getURL('form')); ?></li>
            <li><?php echo CHtml::link(Yii::t('oprecx', 'Interview Slot'),
                $this->getURL('interview')); ?></li>
            <li><?php echo CHtml::link(Yii::t('oprecx',
                        'Division Choices'), $this->getURL('division')); ?></li>
            <li data-role="divider" data-theme="a">Account</li>
            <li><?php echo CHtml::link(Yii::t('oprecx',
                        'Log Out'), array ('/user/logout')); ?></li>

            <?php else: ?>

            <?php endif ?>

        <li data-role="divider" data-theme="a"><?php echo Yii::t('oprecx',
                    'Oprecx') ?></li>
        <li data-icon="home"><?php
            echo CHtml::link(Yii::t('oprecx', 'Home'), array ('/site/index'),
                    array ('data-icon' => 'home', 'data-rel'  => 'external'));
            ?></li>
        <li><?php
            echo CHtml::link(Yii::t('oprecx', 'About'), array ('/site/page', 'view' => 'oprecx'));
            ?></li>
        <li data-theme="e" data-icon="back">
            <?php echo CHtml::link(Yii::t('oprecx', 'Cancel'), '#', array('data-rel'=>'close')); ?>
        </li>
    </ul>
    
       
</div><!-- /panel -->
<div data-role="header" class="head registration">
    <div class="wrapper">
        <h1 class="org-name">
            <a href="#main-panel" data-role="button" data-inline="true" data-iconpos="notext" data-icon="arrow-r" 
               class="ui-icon-alt" data-theme="c" data-mini="true">Menu</a>
            <a href="#main-panel"><?php echo CHtml::encode($this->org->full_name); ?></a>
        </h1>
        <div class="user-name">
            <?php
            if (!$user->isGuest) {
                echo '<span>', CHtml::encode(UserIdentity::getFullName($user->id)), '</span> | ';
                echo CHtml::link(Yii::t('oprecx', 'Log Out'), array ('/user/logout'));
            }
            else {

                echo Yii::t('oprecx', 'Hi, gees! please {reg}/{login}.',
                        array (
                    '{reg}'   => CHtml::link(Yii::t('oprecx', 'register'), $this->getURL('index') . '#userregister'),
                    '{login}' => CHtml::link(Yii::t('oprecx', 'login'), $this->getURL('index') . '#userlogin'),
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

<?php $this->endContent(); ?>