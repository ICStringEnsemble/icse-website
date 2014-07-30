(function(){
    $('.email_buttons button').button();

    var preview_frame = $('#preview_dialog');
    var editor = $('#editable');
    var icse_email = $('#icse_email');
    var ckeditor_localdir = $('#ckeditor_localdir').attr('href');
    preview_frame.dialog({
        autoOpen: false,
        modal: true,
        resizable: true,
        width: 800,
        height: 500
    });

    CKEDITOR.plugins.addExternal('webkit-span-fix', ckeditor_localdir + '/plugins/webkit-span-fix/', 'plugin.js');

    editor.ckeditor(function(){}, {
        extraPlugins : 'webkit-span-fix,sourcedialog,image2,entities',
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
        width: 490,
        image2_alignClasses: [ 'image-left', 'image-center', 'image-right' ],
        image2_captionedClass: 'image-captioned',
        entities_processNumerical: true
    });

    function ui_confirm_and_do(msg, ok_txt, do_it) {
        var outer_dfd = $.Deferred();

        var dialog = $("<div>").html(msg).dialog({
            close: function(){},
            resizable:false,
            modal: true,
            width: 400,
            buttons: {s:function(){}}
        });
        var button_set = dialog.parent().find('.ui-dialog-buttonset');
        dialog.dialog("option", "buttons", [
            {
                text: ok_txt, click: function() {
                    $('.email_buttons .loading_spinner').clone().prependTo(button_set).show();
                    button_set.find('button').button('disable');
                    var inner_dfd = do_it();
                    inner_dfd.done(function(){
                        dialog.dialog("close");
                        outer_dfd.resolve();
                    }).fail(function(){
                        button_set.find('.loading_spinner').remove();
                        button_set.find('button').button('enable');
                    });
                }
            },{
                text: "Cancel", click: function() {
                    dialog.dialog("close");
                }
            }
        ]);
        return outer_dfd.promise();
    }

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
    var save_options = function(){};
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
            if (email_options['email_subject']) {
                email_options_pane.find('#email_subject').val(email_options['subject']);
            }
            if (email_options['send_to_address']) {
                email_options_pane.find('#send_to_address').val(email_options['send_to_address']);
            }
            if (email_options['mailing_list']) {
                mailing_list_radios.filter('[value='+email_options['mailing_list']+']').prop('checked', true);
            }
            if (email_options['send_to_option']) {
                send_to_radios.filter('[value='+email_options['send_to_option']+']').prop('checked', true);
            }
        }
        load_draft();

        save_draft = function() {
            var body = editor.ckeditorGet().getData();
            draft_storage.set('body', body);
            $('.saved_indicator').show();
        };

        save_options = function() {
            draft_storage.set(email_options);
            draft_storage.remove('subject');
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
        // extract text fields
        var email_subject = email_options_pane.find('#email_subject').val();
        var send_to_address = email_options_pane.find('#send_to_address').val();

        // auto-enable/disable fields/radios
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

        // group and save all options
        email_options = {
            email_subject: email_subject,
            mailing_list: mailing_list,
            send_to_option: send_to_option,
            send_to_address: send_to_address
        };
        save_options();

        // edit footer according to options
        icse_email.find('[class^=ml_]').hide();
        icse_email.find('[class^=ml_'+email_options['mailing_list']+']').show();
    }
    handle_email_option_change();
    email_options_pane.find('input').change(handle_email_option_change);

    send_to_tag_box.click(function(){
        send_to_radios.filter('[value=other]').prop('checked', true);
        handle_email_option_change();
    });

    function getEmailDataForPOST(){
        var data = {
            body: editor.ckeditorGet().getData()
        };
        $.extend(data, email_options);
        return data;
    }

    $('button.preview').click(function(){
        preview_frame.dialog('open');
        preview_frame.html($('.email_buttons .loading_spinner').clone().removeAttr('hidden'));
        preview_frame.load(preview_frame.data('base-url'), getEmailDataForPOST());
    });

    $('button.send').click(function(){
        ui_confirm_and_do("Send email?", "Send", function(){
            var dfd = $.Deferred();
            var send_url = $('button.send').data('base-url');
            $.ajax({
                type: 'POST',
                data: getEmailDataForPOST(),
                url: send_url,
                dataType: 'json'
            }).always(function(r){
                if (r.status == "success") {
                    dfd.resolve();
                } else {
                    dfd.reject();
                }
            });
            return dfd;
        });
    });

})();