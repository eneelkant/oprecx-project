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
            //$('#interview-slot-form').trigger('submit');
        });
        $('.time-label-selected').each(function(i, elm) {
            var dis = $(this);
            curSelect[dis.data('slotid')] = dis;
        });
        
        // FIXME: submit button does not respon on popup
        $('.time-label-available').on('tap', function(e) {
            var dis = $(this);
            var curElm = curSelect[dis.data('slotid')];
            if (curElm)
                curElm.removeClass('time-label-selected');
            var input = $('#' + dis.attr('for'));
            input.attr('checked', 'checked');
            dis.addClass('time-label-selected');
            curSelect[dis.data('slotid')] = dis;
            
            $('#selected-time').html(dis.data('time') + ' ' + dis.html());
            $('#interview-time-input').attr('name', input.attr('name'));
            $('#interview-time-input').attr('value', input.attr('value'));
            $('#submit-popup').popup('open', {positionTo: this, transition: 'pop'});
        });
    });
    //*/

})(jQuery, document);

