(function(){

    var pluginName = 'heading-button';

    CKEDITOR.plugins.add(pluginName, {
        init: function(editor) {
            var style = new CKEDITOR.style({element: 'h3'});

            editor.addCommand(pluginName, new CKEDITOR.styleCommand(style));
            editor.attachStyleStateChange(style, function(state){
                !editor.readOnly && editor.getCommand(pluginName).setState(state);
            });

            editor.ui.addButton( 'HeadingButton', {
                label: 'Heading',
                command: pluginName,
                toolbar: 'styles,5'
            });
        }
    });

})();