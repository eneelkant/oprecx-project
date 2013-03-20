<?php
/* @var $this DefaultController */
/* @var $org Organizations */

$this->breadcrumbs = array(
    $this->module->id,
);

?>
<style>
    
@media all and (max-width: 40em){
    .register-login .ui-block-a, .register-login .ui-block-b {
        width: 99.99%;
    }
    .register-login .ui-block-a {
        border-bottom: 1px solid #888;
        padding-bottom: .5em;
        margin-bottom: .5em;
    }
}
@media all and (min-width: 40em){
    .register-login .ui-block-a form {
        border-right: 1px solid #888;
    }
    .register-login form {
        padding: 10px;
    }
}
</style>
<h2><?php echo CHtml::encode($org->full_name); ?></h2>
<p>
    <?php echo CHtml::encode($org->description); ?>
</p>
<?php echo CHtml::link('Daftar', array('division', 'org' => $org->name), array('inline' => true, 'icon' => 'check')) ?>
<div class="ui-grid-a register-login">
    <div class="ui-block-a">
        <form method="get">

            <label for="register-name">Nama Lengkap</label>
            <input type="text" name="RegistrationForm[full_name]" />

            <label for="register-email">Email</label>
            <input type="text" name="RegistrationForm[full_name]" />

            <label for="register-psw">Password</label>
            <input type="text" name="RegistrationForm[full_name]" />

            <label for="register-psw2">Ulangi Passoword</label>
            <input type="text" name="RegistrationForm[full_name]" />

            <input type="submit" value="Daftar" data-theme="b">
        </form>
    </div>
    <div class="ui-block-b">
        <form method="get">

            <label for="register-email">Email</label>
            <input type="text" name="RegistrationForm[full_name]" />

            <label for="register-psw">Password</label>
            <input type="text" name="RegistrationForm[full_name]" />

            <input type="submit" value="Login" data-theme="b">
            <a href="#">Lupa password?</a>
        </form>
    </div>
</div>


<p>
    You may customize this page by editing <tt><?php echo __FILE__; ?></tt>
</p>