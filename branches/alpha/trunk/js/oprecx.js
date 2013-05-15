/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


(function($, doc) {
    $('html').removeClass('no-js').addClass('with-js');
    $('#main-load').fadeOut();
    $('#body').removeClass('loading');


    //*
    $(doc).on('pageshow', '#page-registration-default-interview', null, function(e) {
        var curSelect = [];
        $('#submit-button-alt').click(function() {
            $('#interview-slot-form').trigger('submit');
        });
        $('.time-label-selected').each(function(i, elm) {
            var $this = $(this);
            curSelect[$this.data('slotid')] = $this;
        });
        
        // FIXME: submit button does not respon on popup
        $('.time-label-available').on('tap', function(e) {
            var $this = $(this);
            var curElm = curSelect[$this.data('slotid')];
            if (curElm)
                curElm.removeClass('time-label-selected');
            
            var $input = $('#' + $this.attr('for'));
            $input.attr('checked', 'checked');
            $this.addClass('time-label-selected');
            curSelect[$this.data('slotid')] = $this;
            
            $('#selected-time').html($this.data('time') + ' ' + $this.html());
            $('#interview-time-input').attr('name', $input.attr('name'));
            $('#interview-time-input').attr('value', $input.attr('value'));
            setTimeout(function(){
                $('#submit-popup').popup('open', {positionTo: $this});
            }, 200);
            
        });
    });
    //*/

})(jQuery, document);

