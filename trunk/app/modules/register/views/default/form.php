<?php
/* @var $this DefaultController */
/* @var $org Organizations */
/* @var $fields mixed */

$this->breadcrumbs = array(
    $this->module->id,
);
?>
<h2><?php echo CHtml::link(CHtml::encode($org->full_name), array('index', 'org' => $org->name)); ?></h2>

<form>
    <?php
    foreach ($fields as $field) {
        $options = json_decode($field['options'], true);
        switch ($field['type']) {
            case 'select':
                //print_r($options);
                echo CHtml::dropDownList($field['name'], null, $options['options']);
                break;

            default:
                break;
        }
    }
    ?>
</form>


<p>
    
</p>
<p>
    You may customize this page by editing <tt><?php echo __FILE__; ?></tt>
</p>