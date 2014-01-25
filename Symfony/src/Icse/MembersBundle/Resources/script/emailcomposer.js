(function(){
    $('.email_buttons button').button();

    var preview_frame = $('#preview_frame');
    var editor = $('#editable');
    preview_frame.dialog({
        autoOpen: false,
        modal: true,
        resizable: true,
        width: 800,
        height: 500
    });
    $('button.preview').click(function(){
        preview_frame.dialog('open');
        preview_frame.attr('src', preview_frame.data('base-url')+'?'+$.param({'body': editor.ckeditorGet().getData()}));
    });

    editor.ckeditor(function(){}, {
        extraPlugins : 'webkit-span-fix,sourcedialog',
        customConfig : '',
        extraAllowedContent: '*[id](*)',
        toolbar :
            [
                { name: 'basicstyles', items : [ 'Bold','Italic','Underline','-','RemoveFormat' ] },
                { name: 'paragraph', items : [  ] },
                { name: 'insert', items : [ 'NumberedList','BulletedList', '-', 'Link', 'Image','HorizontalRule' ] },
                '/',
                { name: 'styles', items : [ 'Format' ] },
                /*{ name: 'document', items : [ 'Save','NewPage','DocProps','Preview','Print','-','Templates' ] },*/
                /*{ name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },*/
                { name: 'editing', items : [ 'Sourcedialog', '-', 'Scayt' ] },
                { name: 'tools', items : [ 'Maximize', 'ShowBlocks','-','About' ] }
            ],
        width: 490
    });

    function localStorageAvailable(){
        var test = 'test';
        try {
            localStorage.setItem(test, test);
            localStorage.removeItem(test);
            return true;
        } catch(e) {
            return false;
        }
    }

    var email_options;
    var email_options_pane = $('#email_options_pane');
    var mailing_list_radios = email_options_pane.find('input[name=mailing_list]');
    var send_to_radios = email_options_pane.find('input[name=send_to_option]');

    var save_draft = function(){};
    var save_options = function(opts){};
    if (localStorageAvailable()) {
        var draft_storage = $.initNamespaceStorage('icse_email_draft').localStorage;

        function load_draft() {
            var draft_body = draft_storage.get('body');

            if (draft_body) {
                draft_body = $('<div>').append(draft_body);
                var template_body = $('<div>').append(editor.ckeditorGet().getData());

                var new_rehearsals_section = template_body.find('#email_upcoming_rehearsals');
                var draft_rehearsals_section = draft_body.find('#email_upcoming_rehearsals');
                if (draft_rehearsals_section.length) {
                    draft_rehearsals_section.replaceWith(new_rehearsals_section);
                } else {
                    draft_body.append(new_rehearsals_section)
                }

                editor.ckeditorGet().setData(draft_body.html());
            }

            email_options = draft_storage.get(['subject','mailing_list','send_to_option','send_to_address']);
            email_options_pane.find('#email_subject').val(email_options['subject']);
            email_options_pane.find('#send_to_address').val(email_options['send_to_address']);
            mailing_list_radios.filter('[value='+email_options['mailing_list']+']').prop('checked', true);
            send_to_radios.filter('[value='+email_options['send_to_option']+']').prop('checked', true);
        }
        load_draft();

        save_draft = function() {
            var body = editor.ckeditorGet().getData();

            draft_storage.set('body', body);

            $('.saved_indicator').show();
        };

        save_options = function(opts) {
            draft_storage.set(opts);
        }
    }

    var typing_timer;
    editor.ckeditorGet().on("change", function(){
        $('.saved_indicator').hide();
        clearTimeout(typing_timer);
        typing_timer = setTimeout(save_draft, 1000);
    });

    email_options_pane.find('#send_to_address').tagit({
        availableTags: ["icse@imperial.ac.uk"],
        autocomplete: {delay: 0, minLength: 1}
    });
    var send_to_tag_box = email_options_pane.find('#send_to_row').find('ul.tagit');

    function handle_email_option_change(){
        var email_subject = email_options_pane.find('#email_subject').val();
        var send_to_address = email_options_pane.find('#send_to_address').val();

        var mailing_list = mailing_list_radios.filter(':checked').val();
        if (mailing_list == 'none') {
            send_to_radios.filter('[value=mailing_list]').prop('disabled', true);
            send_to_radios.filter('[value=other]').prop('checked', true);
        } else {
            send_to_radios.filter('[value=mailing_list]').prop('disabled', false);
        }

        var send_to_option = send_to_radios.filter(':checked').val();
        if (send_to_option == 'other') {
            send_to_tag_box.removeClass('disabled');
        } else {
            send_to_tag_box.addClass('disabled');
        }

        email_options = {
            subject: email_subject,
            mailing_list: mailing_list,
            send_to_option: send_to_option,
            send_to_address: send_to_address
        };
        save_options(email_options);
    }
    handle_email_option_change();
    email_options_pane.find('input').change(handle_email_option_change);
    send_to_tag_box.click(function(){
        send_to_radios.filter('[value=other]').prop('checked', true);
        handle_email_option_change();
    });

})();