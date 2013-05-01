<?php
/* @var $this AdminController */
//O::app()->bootstrap->registerButton();
?>
<?php
    $this->widget('SortForm', array(
        'title' => O::t('oprecx', 'Divisions'),
        'addButtonText' => O::t('oprecx', 'Add Division'),
        'formId' => 'division-list',
        'listId' => 'division-list-list',
        'listItemData' => $this->getDivList(),
        'listItemView' => '_division_item',
        'action' => array('saveDivisionList'),
    ))
?>

<!-- Modal -->
<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array (
            'id'   => 'division-edit',
            'type' => 'horizontal',
            'action' => array('saveDivision'),
        ));
?>
<div id="editDivisionDialog" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="editDialog" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3><?php echo O::t('oprecx', 'Edit Division'); ?></h3>
    </div>
    <div class="modal-body">
        <input type="hidden" id="DivisionId" name="Division[div_id]" />
        <div class="control-group">
            <label for="DivisionName" class="control-label"><?php echo O::t('oprecx', 'Name'); ?></label>
            <div class="controls"><input type="text" id="DivisionName" name="Division[name]" /></div>
        </div>
        <div class="control-group">
            <label for="DivisionDescription" class="control-label"><?php echo O::t('oprecx', 'Description'); ?></label>
            <div class="controls"><textarea type="text" id="DivisionDescription" name="Division[description]"></textarea></div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo O::t('oprecx', 'Cancel'); ?></button>
        <button class="btn btn-primary" id="btn-division-save"><?php echo O::t('oprecx', 'Save'); ?></button>
    </div>
</div>
<?php $this->endWidget(); ?>

<div id="deleteDivisionDialog" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="editDialog" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3><?php echo O::t('oprecx', 'Delete Division'); ?></h3>
    </div>
    <div class="modal-body">
        <?php echo O::t('oprecx', 'Are you sure you want to remove "{division}"? All data about this division will be removed.', 
                array('{division}' => '<strong id="divisionRemoveName"></strong>')); ?>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo O::t('oprecx', 'Cancel'); ?></button>
        <button class="btn btn-danger" id="btn-division-remove"><?php echo O::t('oprecx', 'Delete'); ?></button>
    </div>
</div>

<script>
jQuery(function($){
    $('.action-edit').click(function(){
      var div_id = $(this).attr('data-divid');
      $('#DivisionName').val($('#division-name-' + div_id).text().trim());
      $('#DivisionDescription').val($('#division-description-' + div_id).text().trim());
      $('#DivisionId').val(div_id);
    });

    $('#division-list .btn-item-add').click(function(){
      $('#DivisionName').val('');
      $('#DivisionDescription').val('');
      $('#DivisionId').val(0);

      $('#editDivisionDialog').modal('show');
    });

    $('#division-edit').submit(function() {
       var saveText = $('#btn-division-save').html();
      $('#btn-division-save').attr('disabled', true);
      $('#btn-division-save').html('Loading...');
      $(this).ajaxSubmit({
          dataType : 'json',
          success : function(data) {
              var div_id = data.div_id;
              if ($('#division-item-' + div_id).length === 0) {
                  $("#division-list-list").append($(data.html));
              } else {
                  $(data.html).replaceAll('#division-item-' + div_id);
              }

              $('#btn-division-save').attr('disabled', false);
              $('#btn-division-save').html(saveText);
              $('#editDivisionDialog').modal('hide');
          }
      });
       return false;
    });


    $('.action-delete').click(function(){
      var div_id = idToRemove = $(this).attr('data-divid');
      $('#divisionRemoveName').html($('#division-name-' + div_id).html().trim());
    });

    $('#btn-division-remove').click(function(){
        $('#division-item-' + idToRemove).remove();
        $('#division-list')[0].setModified(true);
        $('#deleteDivisionDialog').modal('hide');
    })
});
    //$( "#division-list" ).disableSelection();
</script>