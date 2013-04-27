<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$dataProvider = new CActiveDataProvider('Divisions',
        array (
    'criteria'   => array (
        'select'    => 'div_id, name, description',
        'condition' => 'org_id = ' . $this->org->id,
        'order'     => 'created DESC, name',
        'params'    => array ('org_id' => $this->org->id),
    ),
    'pagination' => array (
        'pageSize' => 20,
    ),
        ));

Yii::import('bootstrap.widgets.TbButtonColumn');

class DivisionButtonColumn extends TbButtonColumn
{

    protected function renderButton($id, $button, $row, $data)
    {
        if ($id == 'update') {
            //(! isset($button['options'])) or ($button['options'] = array());
            $button['options']['data-divId'] = $data['div_id'];
        }
        parent::renderButton($id, $button, $row, $data);
    }

}
?>
<?php
$this->widget('ext.bootstrap.widgets.TbExtendedGridView',
        array (
    'sortableRows'        => true,
    //'sortableAjaxSave' => true,
    'sortableAction'      => 'divisionSort',
    'afterSortableUpdate' => 'js:function(id, position){ console.log(id, position);}',
    'type'                => 'striped bordered',
    'dataProvider'        => $dataProvider,
    'template'            => "{items}",
    'columns'             => array (
        array ('name'   => 'name', 'header' => Yii::t('admin', 'Division Name')),
        array ('name'   => 'description', 'header' => Yii::t('admin', 'Description')),
        array ('name'   => 'div_id', 'header' => 'Language'),
        array (
            'class'               => 'DivisionButtonColumn',
            'template'            => '{update} {delete}',
            'updateButtonUrl'     => '"#editDivisionDialog"',
            'updateButtonOptions' => array ('data-toggle' => 'modal', 'class'       => 'division-edit-button'),
            'htmlOptions'         => array ('style' => 'width: 50px'),
        ),
    ),
));
?>
<!-- Modal -->
<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm',
        array (
    'id'   => 'division-edit',
    'type' => 'horizontal',
        ));
?>
<div id="editDivisionDialog" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="editDialog" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>Edit Division</h3>
    </div>
    <div class="modal-body">
        <input type="hidden" id="DivisionId" name="id" />
        <div class="control-group">
            <label for="DivisionName" class="control-label" >Name</label>
            <div class="controls"><input type="text" id="DivisionName" name="name" /></div>
        </div>
        <div class="control-group">
            <label for="DivisionDescription" class="control-label" >Description</label>
            <div class="controls"><textarea type="text" id="DivisionDescription" name="description"></textarea></div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
        <button class="btn btn-primary">Save changes</button>
    </div>
</div>
<?php $this->endWidget(); ?>

<script>
    jQuery('.division-edit-button').click(function() {
        var cells = $(this.parentElement.parentElement).find('td');
        $('#DivisionName').val($(cells[0]).html());
        $('#DivisionDescription').val($(cells[1]).html());
        $('#DivisionId').val($(this).attr('data-divId'));
    });
</script>