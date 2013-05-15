<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<h1>Conratulation!</h1>
You have successfully registered on this recruitment.
<?php
JqmTag::buttonLink(O::t('oprecx', 'Review my registration'), $this->getURL('index'))->theme('b')->render(true);
JqmTag::buttonLink(O::t('oprecx', 'Log Out'), array('/user/logout'))->render(true);
?>
