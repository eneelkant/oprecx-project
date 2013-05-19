/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(function($){
    var win = window;
    $("#share-link-url").on('mouseenter', function() {
        $(this).select();
    }); 
    $('#shareOrgLink .share-link').click(function(){
        var
            w = 600, h = 350,
            winW = win.outerWidth || win.innerWidth || win.screen.availWidth || w + 50,
            winH = win.outerHeight || win.innerHeight || win.screen.availHeight || h + 20,
            winX = win.screenX || win.screenLeft || win.screen.availLeft || 0,
            winY = win.screenY || win.screenTop || win.screen.availLeft || 0,

            x = ((winW - w) >> 1) + winX, y = ((winH - h) >> 1) + winY,
            arg = 'menubar=0,location=0,toolbar=0,fullscreen=0,left=' + x + ',top=' + y + ',width=' + w + ',height=' + h,
            newwin = win.open('about:blank', null, arg);

        if (newwin){
            newwin.location.replace(this.href);
            return false;
        }
        else return true;
    });
});

jQuery(function($){
    var msg = window.msg || {};
    
   var modifiedFormCount = 0,
       modifiedIndex = [],
       modifiedMessage = msg['sort-form-mofied'] || 'List have been modified',
       saveButtons = [];
   
   function setModified(i, modified){
       if (modifiedIndex[i] && !modified) {
           modifiedFormCount--;
       }
       else if (!modifiedIndex[i] && modified) {
           modifiedFormCount++;
       }
       saveButtons[i].attr('disabled', !modified);
       modifiedIndex[i] = modified;
   }
   
   window.onbeforeunload = function() {
       if (modifiedFormCount > 0)
           return modifiedMessage;
   };
   $('.oprecx-sort-form').each(function(i, elm){
       var $elm = $(elm);
       var $list = $elm.find('.oprecx-sort-list');
       saveButtons[i] = $elm.find('.save-button');
       elm.setModified = function(m) {
            setModified(this.getAttribute('data-sort-id'), m);
       }
       
       $elm.attr('data-sort-id', i);
       $elm.submit(function(){
           var elm_id = $(this).attr('data-sort-id');
           var saveButton = saveButtons[elm_id];
           
           var saveText = saveButton.html();
            saveButton.attr('disabled', true);
            saveButton.html('Loading...');
            $(this).ajaxSubmit({
                dataType : 'json',
                success : function(data) {
                    saveButton.html(saveText);
                    setModified(i, false);
                    
                }
            });

            return false;
       });
       
       $list.attr('data-sort-id', i);
       $list.sortable({
            handle: '.handler',
            update: function(e, ui) {
                setModified($(this).attr('data-sort-id'), true);
            }
        });
        
        $elm.on('click', '.action-delete', function(e){
            if (confirm('Are you sure?')) {
                $target = $('#' + e.currentTarget.getAttribute('data-target-id'));
                $target.remove();
                elm.setModified(true);
            }
            return false;
        });
   });
});