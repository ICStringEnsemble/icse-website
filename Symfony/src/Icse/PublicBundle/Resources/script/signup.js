$(document).ready(function() {
    var slideTime = 300;
    var isAPlayer = null;
    var username_or_email_looked_up = null;
    $('.never_show').hide();

    /*
     * Function to update the form layout, to show and hide elements according to the current state
     */
    function update_form_layout(slideTime) {
        if (username_or_email_looked_up === null) {
            $('.show_if_email, .show_if_username, .show_if_player, #username_or_email_error').slideUp(slideTime);
        } else if (username_or_email_looked_up === 'email') {
            $('.show_if_email').slideDown(slideTime);
        } else if (username_or_email_looked_up === 'username') {
            $('.show_if_username').slideDown(slideTime);
        } else if (username_or_email_looked_up === 'error') {
            $('#username_or_email_error').slideDown();
        }

        if (isAPlayer !== null) {
            $('#username_or_email_lookup').slideDown(slideTime);
        }

        if (username_or_email_looked_up === 'email' || username_or_email_looked_up === 'username') {
            if (isAPlayer) {
                $('.show_if_player').slideDown(slideTime);
            } else {
                $('.show_if_player').slideUp(slideTime);
            }
        }
    }

    /*
     * Function to perform AJAX lookup of username/email, and update the state
     */
    function lookup_username_or_email_action(slideTime){
        if (username_or_email_looked_up === null){
            $('#username_or_email_lookup input').prop('disabled', true);
            var input = $('#username_or_email_field').val();
            $.getJSON(Routing.generate('IcsePublicBundle_query_username'), {input: input}, function(jsonData){
                if (jsonData['type'] == 'email'){
                    username_or_email_looked_up = 'email'
                    $('#form_email').val(input);
                    $('#form_login').val("");
                    $('#form_department').val("");
                } else if (jsonData['type'] == 'username'){
                    username_or_email_looked_up = 'username'
                    $('#form_first_name').val(jsonData['first_name']);
                    $('#form_last_name').val(jsonData['last_name']);
                    $('#form_email').val(jsonData['email']);
                    $('#form_login').val(input);
                    $('#form_department').val(jsonData['department']);
                } else {
                    username_or_email_looked_up = 'error'
                }
                update_form_layout(slideTime);
                $('#username_or_email_lookup input').prop('disabled', false);
            });
        }
    }

    /*
     * Attach handlers to perform lookup on button press, or reset if text is changed
     */
    $('#username_or_email_lookup #lookup_button').click(function(){
        lookup_username_or_email_action(slideTime);
    });
    $('#username_or_email_field').keyup(function(e){
        if(e.keyCode < 13 || e.keyCode == 32 || e.keyCode > 40) {
            username_or_email_looked_up = null;
            update_form_layout(slideTime);
        } else if (e.keyCode == 13) {
            lookup_username_or_email_action(slideTime);
        }
    });
    $('#username_or_email_field').bind('paste', function(){
        username_or_email_looked_up = null;
        update_form_layout(slideTime);
    }); 

    /*
     * Update instrument requiredness
     */
    function update_requiredness_instrument() {
        if (isAPlayer) {
            if ($('input[name="form[instrument][]"]').is(':checked')) {
                $('input[name="form[instrument][]"]').prop('required', false);
            } else {
                $('input[name="form[instrument][]"]').prop('required', true);
            }
        } else {
            $('input[name="form[instrument][]"]').prop('required', false);
        }
    }
    $('#form_instrument').change(update_requiredness_instrument);

    /*
     * Handler for selecting whether the user is a player, or just wants to come to concerts
     */
    function changeIsAPlayerHandler(slideTime) {
        if ($('#form_player input[value="1"]').is(':checked')){
            isAPlayer = true;
            $('#form_standard').prop('required', true);
            enableDisableOtherInstrument(); 
        } else if ($('#form_player input[value="0"]').is(':checked')) {
            isAPlayer = false;
            $('#form_standard').prop('required', false);
            $('#form_other_instrument').prop('required', false); 
        } else {
            isAPlayer = null;
        }
        update_requiredness_instrument() 
        update_form_layout(slideTime);
    }
    $('#form_player').change(slideTime, changeIsAPlayerHandler);

    /*
     * Handler for click on "other" field
     */
    function enableDisableOtherInstrument() {
        if ($('#form_instrument input[value="other"]').is(':checked')){
            $('#form_other_instrument').prop('disabled', false);
            $('#form_other_instrument').prop('required', true);
        } else {
            $('#form_other_instrument').prop('disabled', true);
            $('#form_other_instrument').prop('required', false);
        }
    };
    $('#form_instrument').change(enableDisableOtherInstrument); 

    /*
     * Get into correct starting state
     */
    if ($('#username_or_email_field').val() != "") {
        lookup_username_or_email_action(0);
    }
    enableDisableOtherInstrument();
    changeIsAPlayerHandler(0);
    update_form_layout(0);
});
