<?php
/* @var $this FormController */
/* @var $forms OrgForm[] */
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<?php
    $this->widget('SortForm', array(
        'title' => O::t('oprecx', 'Registration Form'),
        'listItemData' => $forms,
        'listItemView' => '_form_item',
        'action' => array('sort'),
    ))
?>


<?php $this->renderPartial('../division_select', array('id' => 'divlist')); ?>
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
        <h3>Edit Division</h3>
    </div>
    <div class="modal-body">
        <input type="hidden" id="DivisionId" name="Division[div_id]" />
        <div class="control-group">
            <label for="DivisionName" class="control-label" >Name</label>
            <div class="controls"><input type="text" id="DivisionName" name="Division[name]" /></div>
        </div>
        <div class="control-group">
            <label for="DivisionDescription" class="control-label" >Description</label>
            <div class="controls"><textarea type="text" id="DivisionDescription" name="Division[description]"></textarea></div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
        <button class="btn btn-primary" id="btn-division-save">Save changes</button>
    </div>
</div>
<?php $this->endWidget(); ?>

<div id="deleteDivisionDialog" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="editDialog" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>Delete Division</h3>
    </div>
    <div class="modal-body">
        <?php echo Yii::t('admin', 'Are you sure you want to remove "{division}"? All data about this division will be removed.', 
                array('{division}' => '<strong id="divisionRemoveName"></strong>')); ?>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">No</button>
        <button class="btn btn-danger" id="btn-division-remove">Yes</button>
    </div>
</div>

<script>
    jQuery(function($){
        var idToRemove;
        var closeMessage = null;
        
        
        window.onbeforeunload = function(){
            return closeMessage;
        }
        
        window.setModified = function setModified() {
            closeMessage = "<?php echo addslashes(Yii::t('admin', 'There are changes that have not been saved. Click save button to save it')); ?>";
            $('#btn-sort-save').attr('disabled', false);
        }
        
        $('#division-sort').submit(function() {
            var saveText = $('#btn-sort-save').html();
            $('#btn-sort-save').attr('disabled', true);
            $('#btn-sort-save').html('Loading...');
            $(this).ajaxSubmit({
                dataType : 'json',
                success : function(data) {
                    $('#btn-sort-save').html(saveText);
                    closeMessage = null;
                }
            })

            return false;
          });
          /*
          $(".sortable").sortable({
                //placeholder: "ui-state-highlight"
                handle: '.handler',
                update: function(e, ui) {
                    setModified();
                }

            });
        //*/

          $('.action-edit').click(function(){
            var div_id = $(this).attr('data-divid');
            $('#DivisionName').val($('#division-name-' + div_id).text().trim());
            $('#DivisionDescription').val($('#division-description-' + div_id).text().trim());
            $('#DivisionId').val(div_id);
          });

          $('.btn-division-add').click(function(){
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
                        $("#division-list").append($(data.html));
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
              setModified();
              $('#deleteDivisionDialog').modal('hide');
          })
      });
    //$( "#division-list" ).disableSelection();
</script>