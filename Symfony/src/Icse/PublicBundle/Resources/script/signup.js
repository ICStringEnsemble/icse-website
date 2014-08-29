(function($) {

    $.fn.shuffleChildren = function(){
        this.each(function(){
            $(this).children().sort(function(){
                return Math.random() - 0.5;
            }).detach().appendTo(this);
        });
        return this;
    };

    $(document).ready(function() {

        var SLIDE_TIME = 300;
        var freshersfair = $('form#join_us').hasClass('freshersfair');
        var page_prototype = $('section').clone();

        var is_a_player;
        var username_or_email_looked_up;

        /*
         * Function to update the form layout, to show and hide elements according to the current state
         */
        function update_form_layout(slide_time) {
            if (username_or_email_looked_up === null) {
                $('.show_if_email, .show_if_username, .show_if_player, #username_or_email_error').slideUp(slide_time);
            } else if (username_or_email_looked_up === 'email') {
                $('.show_if_email').slideDown(slide_time);
            } else if (username_or_email_looked_up === 'username') {
                $('.show_if_username').slideDown(slide_time);
            } else if (username_or_email_looked_up === 'error') {
                $('#username_or_email_error').slideDown();
            }

            if (is_a_player !== null) {
                $('#username_or_email_lookup').slideDown(slide_time);
            }

            if (username_or_email_looked_up === 'email' || username_or_email_looked_up === 'username') {
                if (is_a_player) {
                    $('.show_if_player').slideDown(slide_time);
                } else {
                    $('.show_if_player').slideUp(slide_time);
                }
            }
        }

        /*
         * Function to perform AJAX lookup of username/email, and update the state
         */
        function lookup_username_or_email_action(slide_time){
            if (username_or_email_looked_up === null){
                var lookup_section = $('#username_or_email_lookup');
                var lookup_inputs = lookup_section.find('input');
                var spinner = lookup_section.find('.loading_spinner');
                lookup_inputs.prop('disabled', true);
                spinner.show();
                var input = $('#username_or_email_field').val();
                $.ajax({
                    dataType: "json",
                    url: Routing.generate('IcsePublicBundle_query_username'),
                    data: {input: input}
                }).done(function(jsonData){
                    if (jsonData['type'] == 'email'){
                        username_or_email_looked_up = 'email';
                        $('#form_email').val(input);
                        $('#form_login').val("");
                        $('#form_department').val("");
                    } else if (jsonData['type'] == 'username'){
                        username_or_email_looked_up = 'username';
                        $('#form_first_name').val(jsonData['first_name']);
                        $('#form_last_name').val(jsonData['last_name']);
                        $('#form_email').val(jsonData['email']);
                        $('#form_login').val(input);
                        $('#form_department').val(jsonData['department']);
                    } else {
                        username_or_email_looked_up = 'error'
                    }
                    $('.error_list').remove();
                    update_form_layout(slide_time);
                }).always(function(){
                    lookup_inputs.prop('disabled', false);
                    spinner.hide();
                });
            }
        }

        function lookup_username_or_email_with_slide(){
            lookup_username_or_email_action(SLIDE_TIME);
        }

        /*
         * Update instrument requiredness
         */
        function update_requiredness_instrument() {
            var instrument_input = $('input[name="form[instrument][]"]');
            if (is_a_player) {
                if (instrument_input.is(':checked')) {
                    instrument_input.prop('required', false);
                } else {
                    instrument_input.prop('required', true);
                }
            } else {
                instrument_input.prop('required', false);
            }
        }

        /*
         * Handler for selecting whether the user is a player, or just wants to come to concerts
         */
        function changeIsAPlayerHandler(slide_time) {
            if ($('#form_player input[value="1"]').is(':checked')){
                is_a_player = true;
                $('#form_standard').prop('required', true);
                enableDisableOtherInstrument();
            } else if ($('#form_player input[value="0"]').is(':checked')) {
                is_a_player = false;
                $('#form_standard').prop('required', false);
                $('#form_other_instrument').prop('required', false);
            } else {
                is_a_player = null;
            }
            update_requiredness_instrument()
            update_form_layout(slide_time);
        }

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
        }

        function ajaxSubmit() {
            var submit_button = $('input#submit');
            var spinner = $('.loading_spinner.final_submit');
            submit_button.prop('disabled', true);
            spinner.show();
            var form_data = new FormData(this);

            $.ajax({
                type: $(this).attr('method'),
                url: $(this).attr('action'),
                data: form_data,
                dataType: 'html',
                cache: false,
                contentType: false,
                processData: false
            }).always(function(){
                submit_button.prop('disabled', false);
                spinner.hide();
            }).done(function(result){
                $('section').replaceWith($(result).find('section'));
                setUpForm();

                if (freshersfair && $('form').length == 0) {
                    setTimeout(function(){
                        $('section').replaceWith(page_prototype.clone());
                        setUpForm();
                    }, 3000);
                }
            });

            return false;
        }

        function handleUsernameOrEmailKeypress(e){
            if(e.keyCode < 13 || e.keyCode == 32 || e.keyCode > 40) {
                username_or_email_looked_up = null;
                update_form_layout(SLIDE_TIME);
            } else if (e.keyCode == 13) {
                lookup_username_or_email_with_slide();
            }
        }

        function handleUsernameOrEmailPaste(){
            username_or_email_looked_up = null;
            update_form_layout(SLIDE_TIME);
        }

        function setUpForm(){
            is_a_player = null;
            username_or_email_looked_up = null;
            $('.never_show').hide();

            update_form_layout(0);
            enableDisableOtherInstrument();
            changeIsAPlayerHandler(0);
            if ($('#username_or_email_field').val() != "") {
                username_or_email_looked_up = $('#form_login').val() != '' ? 'username' : 'email';
                update_form_layout(0);
            }

            $('#username_or_email_lookup #lookup_button').click(lookup_username_or_email_with_slide);
            $('#username_or_email_field').keyup(handleUsernameOrEmailKeypress);
            $('#username_or_email_field').bind('paste', handleUsernameOrEmailPaste);
            $('#form_instrument').change(update_requiredness_instrument);
            $('#form_instrument').change(enableDisableOtherInstrument);
            $('#form_player').change(SLIDE_TIME, changeIsAPlayerHandler);
            $('form').submit(ajaxSubmit);

            disturbSlideshow();
        }

        var disturbSlideshow = function(){};

        if (freshersfair){
            (function(){

                var timeout = null;
                var html = $('html');
                var slideshow = $('#slideshow.freshersfair');
                var plugin_container = slideshow.find('#plugin_container');
                var slides_prototype = plugin_container.find('#slides').detach();

                function showSlideshow(){
                    if (is_a_player === null) {
                        timeout = null;
                        html.addClass('no-yscrollbar');
                        slideshow.show();
                        var new_slides = slides_prototype.clone();
                        new_slides.find('.slides-container').shuffleChildren();
                        plugin_container.append(new_slides);
                        new_slides.superslides({
                            play: 4000,
                            animation: 'fade',
                            pagination: false
                        });
                    }
                }

                disturbSlideshow = function(){
                    plugin_container.empty();
                    slideshow.hide();
                    html.removeClass('no-yscrollbar');

                    if (timeout !== null) clearTimeout(timeout);
                    timeout = setTimeout(showSlideshow, 5000);
                }

                $(document).on('mousemove click touchstart', disturbSlideshow);

            })();
        }

        setUpForm();
    });


})(jQuery);