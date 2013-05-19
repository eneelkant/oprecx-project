<?php
/* @var $this Controller */
/* @var $form CForm */

$form->activeForm['htmlOptions']['data-ajax'] = 'false';
$form->buttons['register']->attributes['data-theme'] = 'b';

?>
<div class="ui-grid-a register-login">
    <div class="ui-block-a">
        <?php echo $form->render(); ?>
    </div>
    <div class="ui-block-b">
        <form>
            <fieldset><legend>Creating Oprecx account is verry simple</legend></fieldset>
            <p>You only need to provide your full name and valid email address. This email address is used to send you
            notification about your activity and recruitment result. Please note that your full name and email address
            will be used for registration process.</p>
            <p><strong>By creating an Oprecx account</strong>, you have agreed to our terms and Privacy policy.</p>
        </form>
    </div>
</div>