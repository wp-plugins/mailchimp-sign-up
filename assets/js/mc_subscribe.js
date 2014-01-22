// JavaScript Document
jQuery.noConflict();
(function ($) {
    $(function () {
        $(document).click(function (e) {
            if (e.target.id != 'emailSignupBtn' && !$('#emailSignupBtn').find(e.target).length) {
            }

        });

        $("#close").click(function () {
            $(this).parent().fadeOut(300);
        });

        $(document).ready(function () {
	
            $('#invite').submit(function (event) {
                /* stop form from submitting normally */
                event.preventDefault();

                var action = $(this).attr('action');
                $.ajax({
                    url: action,
                    type: 'POST',
                    data: {
                        email: $('#address').attr('value')
                    },
                    success: function (data) {
                        $('#result').html(data + '<div id="close"></div>');
                        $('#result').fadeIn(300);
                        $("#close").click(function () {
                            $(this).parent().fadeOut(300);
                        });
                    },
                    error: function () {
                        $('#result').html('Sorry, an error occurred.' + '<div id="close"></div>');
                        $('#result').fadeIn(300);
                        $("#close").click(function () {
                            $(this).parent().fadeOut(300);
                        });
                    }
                });

                return false;
            });
        });
    });
})(jQuery);