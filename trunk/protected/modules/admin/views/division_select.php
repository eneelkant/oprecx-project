<?php
/* @var $this AdminController */

// var size = options.size();
$script = <<<EOD
$(".div_select").dropdownchecklist({ textFormatFunction: function(options) {
    var selectedOptions = options.filter(":selected"),
        countOfSelected = selectedOptions.size();
    switch(countOfSelected) {
       case 0: return "None";
       case 1: return selectedOptions.text();
       case options.size(): return "All Division";
       default: return countOfSelected + " Division";
    }
} });
EOD;
O::app()->clientScript->registerScriptFile(O::app()->request->baseUrl . '/js/ui.dropdownchecklist-1.4-min.js', CClientScript::POS_END);
O::app()->clientScript->registerScript('division_select', $script, CClientScript::POS_READY);

if (!isset($htmlOptions)) $htmlOptions = array();
if (isset($id)) $htmlOptions['id'] = $id;
if (isset($class)) $htmlOptions['class'] = $class;
else $htmlOptions['class'] = '';
$htmlOptions['class'] .= ' div_select';

if (!isset($selected)) $selected = array();
$selected = array_reverse($selected);

?>
<select multiple="multiple"<?php echo CHtml::renderAttributes($htmlOptions); ?>>
    <?php
    foreach ($this->getDivList() as $div) {
        echo '<option value="', $div->div_id, '"', (isset($selected[$div->div_id]) ? ' selected="selected"' : ''),
                '>', $div->name, '</option>';
    }
    ?>
</select>
