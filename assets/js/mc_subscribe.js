// JavaScript Document
jQuery.noConflict();
(function ($) {
    $(function () {
function mcs_results() {        

var mcs_form = $('#mcs_invite');
var h = $('#mcs_invite').outerHeight() + $('#mcs_result').outerHeight();
var w = $('#mcs_invite').outerWidth();
var top = mcs_form.offset().top;
var left = mcs_form.offset().left;

$('#mcs_result').css( {
    'top': top,
    'left': left, 
    'width': w, 
});



var $box = $('#mcs_result');
var fadeOut = function() {
    clearTimeout(timeout);
    $box.fadeOut('slow');
};
var timeout = setTimeout(fadeOut, 4000);
}
        $(document).click(function (e) {
            if (e.target.id != 'emailSignupBtn' && !$('#emailSignupBtn').find(e.target).length) {
            }

        });

        $("#mcs_close").click(function () {
            $(this).parent().fadeOut(fast);
        });




        $(document).ready(function () {


mainSiteBtnColor = $('a').css("color");
var mcsOptions = {
  
    defaultColor: mainSiteBtnColor,
    // a callback to fire whenever the color changes to a valid color
    change: function(event, ui){},
    // a callback to fire when the input is emptied or an invalid color
    clear: function() {},
    // hide the color picker controls on load
    hide: true,
    // show a group of common colors beneath the square
    // or, supply an array of colors to customize further
    palettes: true
};
 if ( $('.mcs-color-field').length ){
    $('.mcs-color-field').wpColorPicker(mcsOptions);
}


            $('#mcs_result').appendTo('body')
            $('#mcs_invite').submit(function (event) {
                /* stop form from submitting normally */
                event.preventDefault();
                $.ajax({
                   url: mcsAjax.ajaxurl,
                    type: 'POST',
                    data: {
                        action :  'mcs_signup_submit',
                        mcs_email: $('#mcs_address').attr('value'),
                        mcs_button_color: $('#mcs_button_color').attr('value'),
                    },
                    success: function (data) {  
                    
                        $('#mcs_result').html('<div id="mcs_close">X</div>' + data );
                        $('#mcs_result').html('<div id="mcs_close">X</div>' + data );
                        $('#mcs_result').fadeIn(300);
                        $("#mcs_close").click(function () {
                            $(this).parent().fadeOut(300);
                        });
                        
                        mcs_results();
                         $('#mcs_invite').find("input[type=text]").val("");
                    },
                    error: function () {
                        $('#mcs_result').html('Sorry, an error occurred.' + '<div id="close"></div>');
                        $('#mcs_result').fadeIn(300);
                        $("#mcs_close").click(function () {
                            $(this).parent().fadeOut(300);
                                mcs_results();
                        });
                    }
                });

                return false;
            });
        });
    });
})(jQuery);