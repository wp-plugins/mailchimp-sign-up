// JavaScript Document
jQuery.noConflict();
(function ($) {
    $(function () {
function results() {		

var media = $('input#address');
var h = $('input#address').outerHeight(true) + $('#results_pointer').height() + $('#result').height();
var w = $('input#address').width();
var top = media.offset().top - h  + "px";
var left = media.offset().left + "px"

$('#result').css( {
    'top': top, 
	'left': left 
});

var $box = $('#result');
var fadeOut = function() {
    clearTimeout(timeout);
    $box.fadeOut('slow');
};
var timeout = setTimeout(fadeOut, 5000);
}
        $(document).click(function (e) {
            if (e.target.id != 'emailSignupBtn' && !$('#emailSignupBtn').find(e.target).length) {
            }

        });

        $("#close").click(function () {
            $(this).parent().fadeOut(300);
        });




        $(document).ready(function () {
			$('#result').appendTo('body')
            $('#invite').submit(function (event) {
                /* stop form from submitting normally */
                event.preventDefault();
                $.ajax({
                   url: '/wp-admin/admin-ajax.php',
                    type: 'POST',
                    data: {
						action :  'mailchimp_submit',
                        email: $('#address').attr('value'),
                    },
                    success: function (data) {	
					
						$('#result').html(data + '<div id="results_pointer"></div><div id="close"></div>');
                        $('#result').html(data + '<div id="results_pointer"></div><div id="close"></div>');
                        $('#result').fadeIn(300);
                        $("#close").click(function () {
                            $(this).parent().fadeOut(300);
                        });
						
						results();
						
                    },
                    error: function () {
                        $('#result').html('Sorry, an error occurred.' + '<div id="close"></div>');
                        $('#result').fadeIn(300);
                        $("#close").click(function () {
                            $(this).parent().fadeOut(300);
								results();
                        });
                    }
                });

                return false;
            });
        });
    });
})(jQuery);