(function ($) {
    'use strict';


    $(document).ready(function () {

    });
    
    $('#acdhch_nl_email_submit').click(function (e) {
        e.preventDefault();
        //check the input field
        if (!$('#acdhch_nl_email').val()) {
            $('#acdch_nl_result_div').html('Email address field is empty!').addClass('acdhch-error-msg');
            return;
        }
        
        let email = $('#acdhch_nl_email').val();
        let testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
        if (!testEmail.test(email)) {
            $('#acdch_nl_result_div').html('This is not an Email address!').addClass('acdhch-error-msg');
            return;
        }

        $.ajax({
            url: acdhch_nls_widget_obj.ajaxurl,
            data: {
                'action': 'acdhch_nls_ajax_request',
                'email': email,
                'nonce': acdhch_nls_widget_obj.nonce
            },
            success: function (data) {
                var obj = $.parseJSON(data);
                
                if (obj['status'] === true) {
                    // This outputs the result of the ajax request
                    $('#acdch_nl_result_div').html('Subscription done!').addClass('acdhch-ok-msg').removeClass('acdhch-error-msg');
                } else {
                    $('#acdch_nl_result_div').html('The email already registered!').addClass('acdhch-error-msg').removeClass('acdhch-ok-msg');
                }
            },
            error: function (errorThrown) {
                $('#acdch_nl_result_div').html('Subscription error! Please try it later!').addClass('acdhch-error-msg').removeClass('acdhch-ok-msg');
            }
        });

    });
})(jQuery);         