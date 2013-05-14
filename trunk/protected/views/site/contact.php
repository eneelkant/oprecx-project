<?php
/* @var $this SiteController */
/* @var $regModel ContactForm */
/* @var $form CActiveForm */

$this->pageTitle = O::app()->name . ' - Contact Us';
$this->breadcrumbs = array(
    'Contact',
);
?>

<h1>Contact Us</h1>

<?php if (O::app()->user->hasFlash('contact')): ?>

    <div class="flash-success">
        <?php echo O::app()->user->getFlash('contact'); ?>
    </div>

<?php else: ?>

    <p>
        If you have business inquiries or other questions, please fill out the following form to contact us. Thank you.
    </p>

    <div class="form">

        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'contact-form',
            'enableClientValidation' => true,
            'clientOptions' => array(
                'validateOnSubmit' => true,
            ),
                ));
        ?>

        <p class="note">Fields with <span class="required">*</span> are required.</p>

        <?php echo $form->errorSummary($regModel); ?>

        <div class="row">
            <?php echo $form->labelEx($regModel, 'name'); ?>
            <?php echo $form->textField($regModel, 'name'); ?>
            <?php echo $form->error($regModel, 'name'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($regModel, 'email'); ?>
            <?php echo $form->textField($regModel, 'email'); ?>
            <?php echo $form->error($regModel, 'email'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($regModel, 'subject'); ?>
            <?php echo $form->textField($regModel, 'subject', array('size' => 60, 'maxlength' => 128)); ?>
            <?php echo $form->error($regModel, 'subject'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($regModel, 'body'); ?>
            <?php echo $form->textArea($regModel, 'body', array('rows' => 6, 'cols' => 50)); ?>
            <?php echo $form->error($regModel, 'body'); ?>
        </div>

        <?php if (CCaptcha::checkRequirements()): ?>
            <div class="row">
                <?php echo $form->labelEx($regModel, 'verifyCode'); ?>
                <div>
                    <?php $this->widget('CCaptcha'); ?>
                    <?php echo $form->textField($regModel, 'verifyCode'); ?>
                </div>
                <div class="hint">Please enter the letters as they are shown in the image above.
                    <br/>Letters are not case-sensitive.</div>
                <?php echo $form->error($regModel, 'verifyCode'); ?>
            </div>
        <?php endif; ?>

        <div class="row buttons">
            <?php echo CHtml::submitButton('Submit'); ?>
        </div>

        <?php $this->endWidget(); ?>

    </div><!-- form -->

<?php endif; ?>