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

    var save_draft = function(){};
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
        }
        load_draft();

        save_draft = function() {
            var body = editor.ckeditorGet().getData();

            draft_storage.set('body', body);

            $('.saved_indicator').show();
        }
    }

    var typing_timer;
    editor.ckeditorGet().on("change", function(){
        $('.saved_indicator').hide();
        clearTimeout(typing_timer);
        typing_timer = setTimeout(save_draft, 1000);
    });

})();