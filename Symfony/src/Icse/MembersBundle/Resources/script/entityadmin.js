(function(){

    if(typeof initCreateForm != 'function'){
        window.initCreateForm = function(){};
    }

    if(typeof initEditForm != 'function'){
        window.initEditForm = function(){};
    }

    if(typeof postTableReload != 'function'){
        window.postTableReload = function(){};
    }

    function resetFormInput(el){
        el.wrap('<form>').closest('form')[0].reset();
        el.unwrap();
    }

    function fileInputToButton(input){
        var wrapper = $('<span>', {
            class: 'fileinput-button-wrapper ' + input.attr('class')
        });
        wrapper.append($('<span>', {
            text: input.data('label')
        }));
        wrapper.insertAfter(input);
        wrapper.button();
        input.detach().appendTo(wrapper);
    }

    /* Misc Initialisation */
    $('button').button();

    $('input[type="file"]').each(function(){
        fileInputToButton($(this));
    });
    $('input.time').timepicker({ 'timeFormat': 'g:i a' });
    $('input.date').datepicker({ dateFormat: "dd/mm/yy" });
    $('#edit_form input:submit, #import_csv_form input:submit').hide();
    // Insert dummy field at beginning of form to prevent jqueryui popups from appearing too early
    $('form').each(function(){
        if ($(this).find('input').first().is('.date, .time')) {
            $(this).before('<input type="text" style="width: 0; height: 0; top: -100px; position: absolute;">');
        }
    });
    $('#edit_form').find('input:submit').before('<input type=hidden name="_method" value="POST" >');

    var $html = $('html');
    $html.on("dragenter", function(event) {
        event.preventDefault();
        event.stopPropagation();
    });
    $html.on("dragover", function(event) {
        event.preventDefault();
        event.stopPropagation();
    });
    $html.on("dragleave", function(event) {
        event.preventDefault();
        event.stopPropagation();
    });
    $html.on("drop", function(event) {
        event.preventDefault();
        event.stopPropagation();
    });

    if (typeof fos != 'undefined' && typeof fos.Router != 'undefined'){
        fos.Router.prototype.generateIgnoringExtras = function(name, opt_params, absolute) {
            var path = this.generate(name, opt_params, absolute);
            return path.replace(/\?.*$/, '');
        }
    }

    /* Loading Indicator Handling */
    var startLoading;
    var stopLoading;
    (function(){
        var loadingIndicatorJobs = 0;

        startLoading = function() {
            loadingIndicatorJobs += 1;
            $('.entity_main_buttons .loading_spinner').show();
        }

        stopLoading = function() {
            loadingIndicatorJobs -= 1;
            if (loadingIndicatorJobs == 0) {
                $('.entity_main_buttons .loading_spinner').hide();
            }
        }
    })();

    /* Table Row Selection */
    $.fn.isAnyEntityInstanceSelected = function(){
        return $(this).hasClass('ui-selected');
    };
    $.fn.isExactlyOneEntityInstanceSelected = function(){
        return $(this).filter('.ui-selected').length == 1;
    };

    function hideShowControlButtons(time) {
        if (typeof time === "undefined") time = 200;

        var rows = $('.entity_instance_list .instance');
        if (!rows.isAnyEntityInstanceSelected()) {
            $('.show_if_none_selected').show(time);
            $('.show_if_one_selected, .show_if_any_selected').hide(time);
        } else if (rows.isExactlyOneEntityInstanceSelected()) {
            $('.show_if_one_selected, .show_if_any_selected').show(time);
            $('.show_if_none_selected').hide(time);
        } else {
            $('.show_if_any_selected').show(time);
            $('.show_if_none_selected, .show_if_one_selected').hide(time);
        }
    }
    hideShowControlButtons(0);

    function attachTableHandlers() {
        var list = $('.entity_instance_list');
        var content = list.selectable({
            filter: '.instance',
            stop: hideShowControlButtons
        });

        var _mouseStart = content.data('uiSelectable')['_mouseStart'];
        content.data('uiSelectable')['_mouseStart'] = function(e) {
            _mouseStart.call(this, e);
            this.helper.css({
                "top": -1,
                "left": -1
            });
        };
        list.find('.instance').dblclick(openEditDialog);

        list.find("abbr.timeago").timeago();
        list.find('.instance.just_updated').removeClass('just_updated', 1000);

        var waterfall = list.filter('.waterfall');
        waterfall.masonry();
        waterfall.find('.instance').each(function(){
            var img = $(this).find('img');
            img.addClass('not-loaded');
            img.imagesLoaded().always(function(){
                img.removeClass('not-loaded');
            });
        });
    }
    attachTableHandlers();

    window.onNavColumnToggle = function() {
        $('.entity_instance_list').filter('.waterfall').masonry();
    };

    /* Reload Table */
    function reloadTable(){
        var dfd = $.Deferred();
        $('#entity_instance_list_container').load(currentPath + '/list', function(){
            attachTableHandlers();
            postTableReload();
            stopLoading();
            hideShowControlButtons();
            $('.ui-widget-overlay').height($(document).height());
            dfd.resolve();
        });
        startLoading();
        return dfd.promise();
    }

    function getTableRowById(id){
        return $('.entity_instance_list .instance').filter(function(){
            return $(this).data('entity').id === id;
        });
    }

    /* Dialogs */

    $('#edit_dialog').dialog({
        autoOpen: false,
        modal: true,
        width: 500,
        minWidth: 500,
        height: 500,
        dialogClass: 'edit_dialog',
        buttons: [
            {
                text: "", // eg. "Create", "Save Changes"
                click: function(){
                    var form = $('#edit_form');
                    form.data('reopen', false);
                    form.find('input:submit').click();
                },
                'class': 'submit_button'
            },
            {
                text: "Cancel",
                click: function(){
                    $(this).dialog("close");
                },
                'class': 'cancel_button'
            }
        ]
    });

    function deleteSelectedEntities(){
        getSocialTokens().done(function(){
            $('.delete_dialog .ui-dialog-buttonset .loading_spinner').show();
            $('.delete_dialog .ui-dialog-buttonset button').button('disable');
            var failure = false;
            var ajaxes = $('.entity_instance_list .instance.ui-selected').map(function(){
                var id = $(this).data('entity').id;
                var dfd = $.Deferred();
                $.ajax({
                    type: 'POST',
                    data: {_method: 'DELETE', fb: fb_page_token},
                    url: currentPath + '/' + id,
                    dataType: 'json'
                }).always(function(r){
                    if (r.status != "success") {
                        failure = true;
                    }
                    dfd.resolve();
                });
                return dfd.promise();
            });

            $.when.apply(null, ajaxes).done(function(){
                $('.delete_dialog .ui-dialog-buttonset .loading_spinner').hide();
                $('.delete_dialog .ui-dialog-buttonset button').button('enable');
                if (!failure) {
                    $('#delete_dialog').dialog('close');
                    $('.entity_instance_list .instance.ui-selected').addClass('just_deleted');
                    reloadTable();
                }
            });
        });
    }

    $('#delete_dialog').dialog({
        autoOpen: false,
        resizable: false,
        modal: true,
        width: 400,
        dialogClass: 'delete_dialog',
        buttons : {
            "Delete" : deleteSelectedEntities,
            "Cancel" : function(){
                $(this).dialog("close");
            }
        }
    });

    $('.entity_main_buttons .loading_spinner').clone().prependTo('.ui-dialog-buttonset');

    /* Button Handlers */
    function openCreateDialog(){
        $('.edit_dialog .ui-dialog-buttonset .submit_button .ui-button-text').html('Create');
        $('#edit_dialog').dialog('open').dialog({ title: "Add " + entitySingularTitle });
        var edit_form = $('#edit_form');
        edit_form.attr('method', 'POST');
        edit_form.find('input[name="_method"]').val('POST');
        edit_form.attr('action', currentPath);
        edit_form.find('input, textarea').not(':button, :submit, :reset, :hidden, :radio, :checkbox').val('');
        edit_form.find('.error').remove();
        edit_form.find('.show_if_create').show();
        edit_form.find('.show_if_edit').hide();
        edit_form.data('form_mode', 'create');
        initCreateForm(edit_form);
    }
    $('button.create').click(openCreateDialog);

    function openEditDialog(){
        var selection = $('.entity_instance_list .instance.ui-selected');
        if (selection.length != 1) return;
        var entity = selection.first().data('entity');
        $('.edit_dialog .ui-dialog-buttonset .submit_button .ui-button-text').html('Save Changes');
        $('#edit_dialog').dialog('open').dialog({ title: "Edit " + entitySingularTitle });
        // $('#edit_form').attr('method', 'PUT');
        var edit_form = $('#edit_form');
        edit_form.find('input[name="_method"]').val('PUT');
        edit_form.attr('method', 'POST');
        edit_form.find('.error').remove();
        edit_form.find('.show_if_edit').show();
        edit_form.find('.show_if_create').hide();
        edit_form.data('form_mode', 'edit');
        edit_form.attr('action', currentPath + '/' + entity.id);
        edit_form.find('input, select, textarea').not(':button, :submit, :reset, :hidden, :radio, :checkbox').each(function(){
            var name_attr = $(this).attr('name');
            if (typeof name_attr === "undefined") return;
            var name_array = name_attr.split('[');
            name_array.splice(0,1);
            name_array = $.map(name_array, function(value) {
                return value.split(']')[0];
            });
            var main_name = name_array[0];
            if (entity.hasOwnProperty(main_name)) {
                var value = entity[main_name];
                if (value === false) value = 0;
                else if (value === true) value = 1;
                else if ($(this).hasClass("date")) value = moment(parseInt(value)).format('DD/MM/YYYY');
                else if ($(this).hasClass("time")) {
                    value = moment(parseInt(value)).format('h:mm a');
                    if (main_name == 'starts_at' && entity['is_start_time_known'] === false) value = '';
                }
                else if (typeof value == 'object') {
                    if (value instanceof Array) value = null;
                    else if ($(this).prop("tagName") == "SELECT") value = value.id;
                    else value = value.name;
                }
                $(this).val(value);
            } else {
                $(this).val('');
            }
        });
        initEditForm(edit_form, entity);
    }
    $('button.edit').click(openEditDialog);

    $('input.create[type="file"]').change(function(){
        var handler = $(this).data('change-handler');
        handler(this.files);
        resetFormInput($(this));
    });

    $('button.refresh').click(reloadTable); // Refresh button

    $('button.unselectall, #above_footer, #signupsfilter a').click(function(){ // Unselect all button/area
        $('.entity_instance_list .instance').removeClass('ui-selected');
        hideShowControlButtons();
    });
    $('.entity_main_buttons button').click(function(e){
        e.stopPropagation();
    });

    $('button.submit_and_reopen').click(function(){
        var form = $(this).closest('form');
        form.data('reopen', true);
        form.find('input:submit').click();
        return false;
    });

    $('button.delete').click(function(){ // Delete button
        $('#delete_dialog').dialog('open');
    });

    var attachErrorsToForm = function this_function (form_element, name_stem, errors) {
        for (var key in errors) if (errors.hasOwnProperty(key)) {
            var child = errors[key];
            var field_name = name_stem + '[' + key + ']';

            if (typeof  child == 'string') {
                var error_element = $('<div>',{
                    'class' : 'error',
                    'text' :  child
                });

                var bad_field_element = form_element.find('*[name="'+field_name+'"]');
                if (bad_field_element.length == 0) {
                    bad_field_element = form_element.find('*[name="'+name_stem+'"]');
                }

                if (bad_field_element.length == 0) { // insert as global error
                    if (isNaN(key)) error_element.text(key + ': ' + error_element.text());  // add key to message
                    error_element.addClass('global_error').appendTo(form_element);
                } else { // attach error to bad field
                    bad_field_element.before(error_element);
                }
            }
            else if (typeof child == 'object') {
                this_function(form_element, field_name, child);
            }
        }
    };

    /* Form submit */
    $('.ui-dialog form').submit(function () {
        var form_element = $(this);
        var containing_dialog = form_element.closest('.ui-dialog-content');
        var dialog_buttonpane = containing_dialog.nextAll('.ui-dialog-buttonpane');
        var all_buttons = dialog_buttonpane.add(form_element).find('button');
        dialog_buttonpane.find('.loading_spinner').show();
        all_buttons.each(function(){
            $(this).data('was_disabled', $(this).prop('disabled'));
        });
        all_buttons.button('disable');
        form_element.find('.error').remove();
        var formData, contentType, processData;
        if (form_element.find('input:file').length !== 0) {      // if files, we have to use multipart/form-data; doesn't play nicely with PUT
            formData = new FormData(form_element[0]);
            contentType = false;
            processData = false;
        } else {                                            // if no files, use urlencoded data; can use either POST or PUT
            formData = form_element.serialize();
            contentType = 'application/x-www-form-urlencoded; charset=UTF-8';
            processData = true;
        }
        $.ajax({
            type: form_element.attr('method'),
            url: form_element.attr('action'),
            data: formData,
            dataType: 'json',
            cache: false,
            contentType: contentType,
            processData: processData
        }).always(function(){
            dialog_buttonpane.find('.loading_spinner').hide();
            all_buttons.each(function(){
                if (!$(this).data('was_disabled')) {
                    $(this).button('enable');
                }
            });
        }).done(function(result){
            if (result.status == "success"){
                containing_dialog.dialog('close');
                var on_reload_table = reloadTable();
                if (form_element.data('reopen') === true) {
                    on_reload_table.done(function(){
                        var new_row = getTableRowById(result.entity.id);
                        new_row.addClass('ui-selected');
                        hideShowControlButtons();
                        $('button.edit').click();
                    });
                }
            } else {
                if (result.status == "partial") {
                    reloadTable();
                }
                attachErrorsToForm(form_element, 'form', result.errors);
            }
        }).always(function(){
            form_element.data('reopen', null);
        });
        return false;
    });

    /* Music Library */
    (function(){
        var edit_form = $('#edit_form.piece_of_music');
        if (edit_form.length > 0) {

            var add_files_button = edit_form.find('#add_files_button');
            var add_new_part_form = $('#piece_of_music_add_new_part_form');
            var practice_parts_list = edit_form.find('ul#practice_parts');

            add_new_part_form.find('input[name="form[file]"]').remove();

            var newUploadingPart;
            (function(){
                var uploading_part_prototype = practice_parts_list.find('li.uploading-part').detach();

                newUploadingPart = function(){
                    var item = uploading_part_prototype.clone();
                    item.find('.progress-bar').progressbar({value: false});
                    return item;
                };
            })();

            var newListedPart;
            (function(){
                var practice_part_prototype = practice_parts_list.find('li.part').detach();
                practice_part_prototype.find('input').removeAttr('id');

                practice_part_prototype.find('.a-button.delete').click(function(){
                    $(this).closest('li.part').addTransitionClass('hidden').done(function(){
                        $(this).remove();
                    });
                    return false;
                });

                newListedPart = function(entity){
                    var item = practice_part_prototype.clone(true);
                    item.find('a.open').attr('href', Routing.generateIgnoringExtras('IcsePublicBundle_resource', entity));
                    ['instrument', 'sort_index'].forEach(function(input_name){
                        var input = item.find('input.' + input_name);
                        input.val(entity[input_name]);
                        input.attr('name', input.attr('name').replace('__ID__', entity.id));
                    });
                    return item;
                };
            })();

            var assignSortIndices = function(){
                practice_parts_list.find('li').each(function(){
                    $(this).find('input.sort_index').val($(this).index());
                });
            };

            practice_parts_list.sortable({
                placeholder: "drag-placeholder",
                cancel: 'a,.a-button,input',
                update: assignSortIndices
            });

            var currentMaxSortIndex = function(){
                var indices = practice_parts_list.find('li').map(function(){
                    return $(this).find('input.sort_index').val();
                });
                return indices.length == 0 ? -1 : Math.max.apply(null, indices);
            };

            var handleFile = function(file, button_pane){
                var pane_buttons = button_pane.find('button');
                var loading_spinner = button_pane.find('.loading_spinner');
                var new_sort_index = currentMaxSortIndex()+1;
                add_new_part_form.find('input[name="form[sort_index]"]').val(new_sort_index);
                var uploading_item = newUploadingPart();
                uploading_item.find('.name').text(file.name);
                var progress_bar = uploading_item.find('.progress-bar');
                uploading_item.find('input.sort_index').val(new_sort_index);
                uploading_item.hide();
                practice_parts_list.append(uploading_item);
                uploading_item.show(500);

                var form_data = new FormData(add_new_part_form[0]);
                form_data.append("form[file]", file);

                pane_buttons.button('disable');
                loading_spinner.show();
                var complete = false;

                $.ajax({
                    url: add_new_part_form.attr('action').replace('__ID__', practice_parts_list.data('piece_id')),
                    type: "POST",
                    data: form_data,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    xhr: function() {
                        xhr = $.ajaxSettings.xhr();
                        if(xhr.upload){
                            xhr.upload.addEventListener('progress', function(e) {
                                if(e.lengthComputable){
                                    progress_bar.progressbar({value: e.loaded, max: e.total})
                                }
                            } , false);
                        }
                        return xhr;
                    }
                }).done(function(result){
                    if (result.status == "success"){
                        var new_form_item = newListedPart(result.entity);
                        uploading_item.replaceWith(new_form_item);
                        complete = true;
                    }
                }).always(function(){
                    if (!complete) uploading_item.remove();
                    if (practice_parts_list.find('.uploading-part').length === 0){
                        pane_buttons.button('enable');
                        loading_spinner.hide();
                        assignSortIndices();
                        reloadTable();
                    }
                });
            };

            var handleAllFiles = function (files){
                var button_pane = edit_form.closest('.ui-dialog-content').nextAll('.ui-dialog-buttonpane');
                for (var i=0; i<files.length; i++){
                    handleFile(files[i], button_pane);
                }
            };

            add_files_button.change(function(){
                handleAllFiles(this.files);
                resetFormInput($(this));
            });

            var drop_mask = edit_form.find('#practice_parts_section .drop_mask');
            drop_mask.data('drop-handler', handleAllFiles);

            window.initCreateForm = function() {
                practice_parts_list.empty();
                drop_mask.data("enabled", false);
            };

            window.initEditForm = function(edit_form, piece) {
                drop_mask.data("enabled", true);
                practice_parts_list.data('piece_id', piece.id);
                var practice_parts = piece.practice_parts;
                practice_parts_list.empty();
                practice_parts.forEach(function(this_part){
                    var new_form_item = newListedPart(this_part);
                    practice_parts_list.append(new_form_item);
                });
            };
        }
    })();

    /* Image Library */
    (function(){
        var edit_form = $('#edit_form.image');
        if (edit_form.length > 0) {

            var new_image_form = $('form#new_image');
            new_image_form.find('input[name="form[file]"]').remove();

            var newUploadingImage;
            (function(){
                var uploading_image_prototype = $('#image_prototype').detach().removeProp('hidden');

                newUploadingImage = function(){
                    var item = uploading_image_prototype.clone();
                    item.find('.progress-bar').progressbar({value: false});
                    return item;
                };
            })();

            var handleFile = function(file){
                if (!file.type.match('image.*')) {
                    return;
                }

                startLoading();
                var image_list = $('.entity_instance_list');
                var uploading_item = newUploadingImage();
                var progress_bar = uploading_item.find('.progress-bar');
                image_list.prepend(uploading_item);

                var reader = new FileReader();
                reader.onloadend = function(e){
                    uploading_item.find('img').attr('src', e.target.result);
                    image_list.masonry('prepended', uploading_item);
                    stopLoading();
                };
                reader.readAsDataURL(file);

                var form_data = new FormData(new_image_form[0]);
                form_data.append("form[file]", file);

                $.ajax({
                    url: new_image_form.attr('action'),
                    type: new_image_form.attr('method'),
                    data: form_data,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    xhr: function() {
                        xhr = $.ajaxSettings.xhr();
                        if(xhr.upload){
                            xhr.upload.addEventListener('progress', function(e) {
                                if(e.lengthComputable){
                                    progress_bar.progressbar({value: e.loaded, max: e.total})
                                }
                            } , false);
                        }
                        return xhr;
                    }
                }).always(function(){
                    uploading_item.addClass('instance').removeClass('uploading-instance');
                    if ($('.entity_instance_list').find('.uploading-instance').length === 0){
                        console.log("reload table");
                        reloadTable();
                    }
                });
            };

            var handleAllFiles = function(files){
                for (var i=0; i<files.length; i++){
                    handleFile(files[i]);
                }
            };

            $('section .drop_mask').data('drop-handler', handleAllFiles);
            $('input.create[type="file"]').data('change-handler', handleAllFiles);
        }
    })();

    (function(){
        var all_drop_masks = $('.drop_mask');
        all_drop_masks.each(function(){
            var drop_mask = $(this);
            var container = drop_mask.parent();
            var height_fix_enabled = drop_mask.hasClass('height_fix');

            container.on('dragenter', function(){
                if (drop_mask.data('enabled') === true){
                    container.addClass('draginside');
                    if (height_fix_enabled) drop_mask.height(container.outerHeight());
                }
            });

            drop_mask.on('dragleave', function(){
                container.removeClass('draginside');
            });

            drop_mask.on('drop', function(e){
                container.removeClass('draginside');
                var files = e.originalEvent.dataTransfer.files;
                var drop_handler = drop_mask.data('drop-handler');
                if (typeof drop_handler == "function") drop_handler(files);
            });
        });
    })();

    /* Reusable UI Dialogs */
    function ui_warn(msg) {
        var dfd = $.Deferred();
        $("<div>").html(msg).dialog({
            close: function(){dfd.resolve();},
            resizable:false,
            closeOnEscape: false,
            buttons: {'Okay':function(){$(this).dialog('close');}}
        });
        return dfd.promise();
    }
    function ui_progress(msg) {
        var dfd = $.Deferred();
        var dialog = $("<div>").html(msg).dialog({
            resizable:false,
            closeOnEscape: false,
            open: function() { $(this).parent().find(".ui-dialog-titlebar-close").hide(); },
            modal: true
        });
        dfd.always(function(){
            dialog.dialog('close');
        });
        return dfd;
    }

    /* Facebook */

    var fb_connected = false;
    var fb_page_token = null;
    var fb_perms = ['create_event', 'manage_pages'];

    function processFBLoginResponse(r) {
        fb_connected = (r.status === 'connected');
    }

    if (enable_social_net) {
        $.ajaxSetup({ cache: true });
        $.getScript('//connect.facebook.net/en_UK/all.js', function(){
            FB.init({
                appId: facebookAppId,
                status: true
            });
            FB.Event.subscribe('auth.authResponseChange', processFBLoginResponse);
            $('button.social_sync').button("option", "disabled", false);
        });
    }

    function facebookLogin() {
        var dfd = $.Deferred();
        FB.login(function(r){
            processFBLoginResponse(r);
            if (fb_connected) {
                dfd.resolve();
            } else {
                dfd.reject();
            }
        }, {scope: fb_perms.join(',')});
        return dfd.promise();
    }

    function doWithFacebookLogin(op, dfd) {
        facebookLogin().done(function(){
            op(dfd);
        }).fail(function(){
            dfd.reject();
        });
    }

    function doWithFacebook(op) {
        var dfd = $.Deferred();
        console.log('Already connected:', fb_connected);
        if (fb_connected) {
            op($.Deferred()).done(function(){
                console.log("Success first time");
                dfd.resolve();
            }).fail(function(){
                ui_warn("Click to reconnect to Facebook.").always(function(){
                    doWithFacebookLogin(op, dfd);
                });
            });
        } else {
            doWithFacebookLogin(op, dfd);
        }
        return dfd.promise();
    }

    function getFacebookPageToken() {
        if (fb_page_token !== null) {
            return $.when();
        } else {
            return doWithFacebook(function(dfd){
                FB.api('/me/accounts/',  function(response) {
                    var success = false;
                    if ($.isArray(response.data)) for (var i=0; i<response.data.length; i++) {
                        var page = response.data[i];
                        if (page.id === facebookPageId) {
                            console.log(page.name, page.access_token);
                            fb_page_token = page.access_token;
                            success = true;
                            break;
                        }
                    }
                    success ? dfd.resolve() : dfd.reject();
                });
                return dfd.promise();
            });
        }
    }

    function getSocialTokens() {
        if (enable_social_net) {
            return $.when(getFacebookPageToken());
        } else {
            return $.when();
        }
    }

    $('button.social_sync').button().click(function(){
        getSocialTokens().done(function(){
            var progress = ui_progress("Synchronising changes to social networks...");
            var dfds = $('.entity_instance_list .instance.ui-selected').map(function(){
                var id = $(this).data('entity').id;
                var dfd = $.Deferred();
                $.ajax({
                    type: "POST",
                    url: currentPath + '/' + id +'?'+ $.param({op: 'socialnetsync'}),
                    data: {fb: fb_page_token},
                    dataType: 'json'
                }).always(function(r){
                    if (r.status == "fail") {
                        fb_page_token = null;
                    }
                    dfd.resolve();
                });
                return dfd.promise();
            });
            $.when.apply(null, dfds).done(function(){
                progress.resolve();
                reloadTable();
            })
        });
    });

})();
