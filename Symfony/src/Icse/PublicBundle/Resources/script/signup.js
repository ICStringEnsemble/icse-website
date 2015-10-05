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

        var db;
        const DB_NAME = 'icsedb';
        const DB_VERSION = 1;

        var SLIDE_TIME = 300;
        var freshersfair = $('form#join_us').hasClass('freshersfair');
        var page_prototype = $('section.main').clone();

        var is_a_player;
        var username_or_email_looked_up;

        var all_departments = $([
            'Aeronautics',
            'Bioengineering',
            'Chemical Engineering',
            'Civil and Environmental Engineering',
            'Computing',
            'Dyson School of Design Engineering',
            'Earth Science and Engineering',
            'Electrical and Electronic Engineering',
            'Materials',
            'Mechanical Engineering',
            'Institute of Clinical Sciences',
            'Department of Medicine',
            'National Heart and Lung Institute',
            'School of Public Health',
            'Department of Surgery and Cancer',
            'Lee Kong Chian School of Medicine - London Office',
            'Imperial College Academic Health Science Centre',
            'Imperial College Healthcare NHS Trust',
            'Chemistry',
            'Mathematics',
            'Physics',
            'Life Sciences',
            'Centre for Environmental Policy',
            'Business School',
            'Finance',
            'Innovation and Entrepreneurship',
            'Management',
            'Data Science Institute',
            'Energy Futures Lab',
            'Institute of Global Health Innovation',
            'Grantham Institute - Climate Change and the Environment',
            'Institute for Security Science and Technology',
            'Careers Service',
            'Centre for Academic English',
            'Centre for Languages, Culture and Communication',
            'Educational Development Unit',
            'Graduate School',
            'Library',
            'Post Doc Development Centre',
            'School of Professional Development'
        ]);

        const TIME_OFFSET = (function(){
            var server_time = moment($('#current_server_time').text());
            var client_time = moment();
            return server_time.diff(client_time);
        })();

        window.ON_CHANGE_OFFLINE_MODE = function(){
            var submit_button = $('input#submit');
            if (IN_OFFLINE_MODE) {
                submit_button.prop('disabled', true);
                open_db().done(function(){
                    submit_button.prop('disabled', false);
                });
            } else {
                if ("close" in db) db.close();
                submit_button.prop('disabled', false);
            }
        };

        function open_db() {
            var dfd = $.Deferred();
            var req = indexedDB.open(DB_NAME, DB_VERSION);
            req.onsuccess = function (e) {
                db = e.target.result;
                db.onerror = function(e) {
                    alert("Database error: " + e.target.errorCode);
                };
                dfd.resolve();
            };
            req.onerror = function (e) {
                console.error("open_db:", e.target.errorCode);
                dfd.reject();
            };

            req.onupgradeneeded = function (e) {
                console.log("Upgrading DB");
                db = e.target.result;

                if(!db.objectStoreNames.contains("signup")) {
                    db.createObjectStore("signup", { keyPath: "id", autoIncrement: true });
                }
            };

            req.onblocked = function(e) {
                alert("Please close all other tabs with this site open!");
            };

            return dfd.promise();
        }

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

        function looks_like_email(input) {
            var re = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
            return re.test(input);
        }
        function looks_like_username(input) {
            var re = /^[a-zA-Z0-9]+$/i;
            return re.test(input);
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

                var on_done_success = function(data){
                    if (data['type'] == 'email'){
                        username_or_email_looked_up = 'email';
                        $('#form_email').val(input);
                        $('#form_login').val("");
                        $('#form_department').val("");
                    } else if (data['type'] == 'username'){
                        username_or_email_looked_up = 'username';
                        $('#form_login').val(input);
                        if ("first_name" in data) {
                            $('#form_first_name').val(data['first_name']);
                            $('#form_last_name').val(data['last_name']);
                            $('#form_email').val(data['email']);
                            $('#form_department').val(data['department']);
                        }
                    } else {
                        username_or_email_looked_up = 'error'
                    }
                    $('.error_list').remove();
                    update_form_layout(slide_time);
                    if (slide_time) {
                        setTimeout(function(){
                            $('input#form_first_name').focus();
                        }, 300);
                    }
                };
                var on_done_always = function(){
                    lookup_inputs.prop('disabled', false);
                    spinner.hide();
                };

                if (window.IN_OFFLINE_MODE) {
                    var type =
                        looks_like_email(input)    ? 'email' :
                        looks_like_username(input) ? 'username' :
                                                     'other'
                    ;
                    on_done_success({type: type});
                    on_done_always();
                } else {
                    $.ajax({
                        dataType: "json",
                        url: Routing.generate('IcsePublicBundle_query_username'),
                        data: {input: input}
                    }).done(
                        on_done_success
                    ).always(
                        on_done_always
                    );
                }
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
            if (slide_time) {
                setTimeout(function(){
                    $('input#username_or_email_field').focus();
                }, 300);
            }
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

        function ajaxSubmit(event) {
            event.preventDefault();
            var submit_button = $('input#submit');
            var spinner = $('.loading_spinner.final_submit');
            submit_button.prop('disabled', true);
            spinner.show();
            var form_data = $(this).serializeArray();

            var on_always = function(){
                submit_button.prop('disabled', false);
                spinner.hide();
            };
            var on_done = function(result){
                $('section').replaceWith($(result).find('section'));
                setUpForm();

                if (freshersfair && $('form').length == 0) {
                    setTimeout(function(){
                        $('section').replaceWith(page_prototype.clone());
                        setUpForm();
                    }, 3000);
                }
            };

            if (window.IN_OFFLINE_MODE) {
                var person_name;
                var object = {
                    submitted_at: moment().add(TIME_OFFSET).toISOString(),
                    form_data: form_data
                };

                var re_get_name = /^form\[(\w+)\]/;
                var re_is_array = /^(.*)\[\]$/;

                form_data.forEach(function(o){
                    var name = o.name.replace(re_get_name, "$1");
                    var value = o.value;

                    if (name=="first_name") person_name = value;

                    var is_array_result = re_is_array.exec(name);
                    if (is_array_result !== null) {
                        name = is_array_result[1];
                        if (object.hasOwnProperty(name)) {
                            object[name].push(value);
                        } else {
                            object[name] = [value];
                        }
                    } else {
                        object[name] = value;
                    }
                });

                var t = db.transaction("signup", "readwrite");
                t.objectStore("signup").add(object);

                t.oncomplete = function(e) {
                    var success_page = $('<body>', {
                        html: $('<section>', {
                            class: 'main',
                            html: $('<p>', {
                                text: "Thanks "+person_name+", we'll be in touch shortly."
                            })
                        })
                    });
                    on_always();
                    on_done(success_page);
                };
            } else {
                $.ajax({
                    type: $(this).attr('method'),
                    url: $(this).attr('action'),
                    data: form_data,
                    dataType: 'html',
                    cache: false
                }).always(on_always).done(on_done);
            }
            return false;
        }

        function handleUsernameOrEmailKeypress(e){
            if(e.keyCode < 13 || e.keyCode == 32 || e.keyCode > 40) {
                username_or_email_looked_up = null;
                update_form_layout(SLIDE_TIME);
            } else if (e.keyCode == 13) {
                e.preventDefault();
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

            $('#form_department').autocomplete({
                source: function (context, callback) {
                    callback(
                        all_departments.filter(function (i, a) {
                            return $.fuzzyMatch(a, context.term).score;
                        }).sort(function (a, b) {
                            var score_a = $.fuzzyMatch(a, context.term).score,
                                score_b = $.fuzzyMatch(b, context.term).score;
                            return score_a < score_b ? 1 : score_a === score_b ? 0 : -1;
                        })
                        .slice(0, 3)
                    );
                },
                delay: 1
            });

            $('#username_or_email_lookup #lookup_button').click(lookup_username_or_email_with_slide);
            $('#username_or_email_field').keydown(handleUsernameOrEmailKeypress);
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