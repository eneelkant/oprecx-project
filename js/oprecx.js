/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


jQuery(function($, undefined){
    $('html').first().removeClass('no-js');
    $('#main-load').fadeOut(); 
    $('#body').removeClass('loading');
   
   
   //*
    $(document).on('pageshow', '#page-registration-default-interview', null, function(e) {
        var curSelect = [];
        $('#submit-button-alt').on('tap', function(){
            $('#interview-slot-form').submit();
        });
        $('.time-label-selected').each(function(i, elm) {
            var dis = $(this);
            curSelect[dis.data('slotid')] = dis;
        });
        $('.time-label-available').on('tap', function(e) {
            var dis = $(this);
            var curElm = curSelect[dis.data('slotid')];
            if (curElm) curElm.removeClass('time-label-selected');
            dis.addClass('time-label-selected');
            curSelect[dis.data('slotid')] = dis;
            $('#selected-time').html(dis.data('time') + ' ' + dis.html());
            $('#submit-popup').popup('open', {positionTo: this, transition: 'pop'});
         });
     });
   //*/
  
});

