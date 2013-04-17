<?php
/* @var $this CController */
$user = Yii::app()->user;
?>
<?php $this->beginContent(); ?>
<div data-role="header" class="head">
    <div class="wrapper">
        <h1 class="oprecx-logo">
            <?php echo CHtml::link(CHtml::image(Yii::app()->request->baseUrl . '/images/logo.png',
                            'Oprecx', array('title' => Yii::t('oprecx', 'Goto homepage'))), 
                   Yii::app()->homeUrl); ?>
        </h1>
        <div class="user-name">
            <?php
            if (!$user->isGuest) {
                echo '<span>', CHtml::encode($user->getState('fullname')), '</span> | ';
                echo CHtml::link(Yii::t('oprecx', 'Log Out'), array ('/user/logout'));
            }
            else {

                echo Yii::t('oprecx', 'Hi, gees! please {reg}/{login}.',
                        array (
                    '{reg}'   => CHtml::link(Yii::t('oprecx', 'register'), array('user/register')),
                    '{login}' => CHtml::link(Yii::t('oprecx', 'login'), array('user/login')),
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