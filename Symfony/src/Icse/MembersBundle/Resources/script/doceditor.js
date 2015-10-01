(function(){

    var editors = $('.doceditor');
    var bundles_basedir_parts = $('#bundles_basedir').attr('href').split('?');
    var bundles_basedir = bundles_basedir_parts[0];
    var bundles_version = bundles_basedir_parts[1];
    var main_css = $('#main_stylesheet').attr('href');

    var extra_plugins = 'sourcedialog,image2,entities,autogrow';

    ['webkit-span-fix', 'heading-button'].forEach(function(p){
        CKEDITOR.plugins.addExternal(p, bundles_basedir+'/icsemembers/lib/ckeditor/plugins/'+p+'/', 'plugin.js?'+bundles_version);
        extra_plugins += ',' + p;
    });

    editors.each(function(){
        var source_plugin = 'Sourcedialog';
        var contents_css = '';
        var body_class = '';
        if ($(this).is('textarea')) {
            source_plugin = 'Source'
            contents_css = main_css;
            body_class = 'icseeditorcontent';

            if ($(this).hasClass('newsarticleeditor')) {
                body_class += ' newsarticleeditor';
            }
        }

        $(this).ckeditor(function(){}, {
            extraPlugins : extra_plugins,
            customConfig : '',
            extraAllowedContent: '*[id](*)',
            toolbar :
                [
                    { name: 'styles', items : [ 'HeadingButton' ] },
                    { name: 'basicstyles', items : [ 'Bold','Italic',/*'Underline',*/'-','RemoveFormat' ] },
                    /*{ name: 'document', items : [ 'Save','NewPage','DocProps','Preview','Print','-','Templates' ] },*/
                    /*{ name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },*/
                    { name: 'editing', items : [ 'Scayt', '-', source_plugin ] },
                    '/',
                    { name: 'styles2', items : [ 'Format' ] },
                    { name: 'insert', items : [ 'NumberedList','BulletedList', '-', 'Link', 'Image','HorizontalRule' ] },
                    { name: 'tools', items : [ /*'Maximize',*/ 'ShowBlocks','-','About' ] },
                    //{ name: 'paragraph', items : [  ] },
                ],
            //width: 490,
            image2_alignClasses: [ 'image-left', 'image-center', 'image-right' ],
            image2_captionedClass: 'image-captioned',
            entities_processNumerical: true,
            contentsCss: contents_css,
            bodyClass: body_class
        });
    });

    function IcseDocEditor(obj) {
        this.ckeditor = obj.ckeditorGet();
    }

    IcseDocEditor.prototype.getContent = function() {
        return this.ckeditor.getData();
    };

    IcseDocEditor.prototype.setContent = function(x) {
        return this.ckeditor.setData(x);
    };

    IcseDocEditor.prototype.onChange = function(x) {
        return this.ckeditor.on("change", x);
    };

    $.fn.icseDocEditor = function(){
        return new IcseDocEditor(this);
    };

})();
