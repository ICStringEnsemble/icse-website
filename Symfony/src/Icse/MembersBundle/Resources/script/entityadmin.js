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


    String.prototype.capitalize = function() {
        return this.charAt(0).toUpperCase() + this.slice(1);
    }

    /* Misc Initialisation */
    $('button').button();
    $('input.time').timepicker({ 'timeFormat': 'g:i a' });
    $('input.date').datepicker({ dateFormat: "dd/mm/yy" });
    $('#edit_form input:submit, #import_csv_form input:submit').hide();
    // Insert dummy field at beginning of form to prevent jqueryui popups from appearing too early
    $('form').each(function(){
        if ($(this).find('input').first().is('.date, .time')) {
            $(this).before('<input type="text" style="width: 0; height: 0; top: -100px; position: absolute;">');
        }
    });
    $('#edit_form input:submit').before('<input type=hidden name="_method" value="POST" >');

    /* Loading Indicator Handling */
    var loadingIndicatorJobs = 0; 

    function startLoading() {
        loadingIndicatorJobs += 1;
        $('.table_buttons .loading_spinner').show();
    }

    function stopLoading() {
        loadingIndicatorJobs -= 1;
        if (loadingIndicatorJobs == 0) {
            $('.table_buttons .loading_spinner').hide();
        }
    } 

    /* Table Row Selection */
    $.fn.isAnyTableRowSelected = function(){
        return $(this).hasClass('ui-selected');
    };
    $.fn.areAllTableRowsSelected = function(){
        return $(this).not('thead tr').not('.ui-selected').length == 0;
    };
    $.fn.isExactlyOneTableRowSelected = function(){
        return $(this).filter('.ui-selected').length == 1;
    };

    function hideShowControlButtons(time) {
        if(typeof time === "undefined") {
            time = 200;
        } 
        if (!$('tbody tr').isAnyTableRowSelected()) {
            $('.show_if_none_selected').show(time);
            $('.show_if_one_selected, .show_if_any_selected').hide(time);
        } else if ($('tbody tr').isExactlyOneTableRowSelected()) {
            $('.show_if_one_selected, .show_if_any_selected').show(time);
            $('.show_if_none_selected').hide(time);
        } else {
            $('.show_if_any_selected').show(time);
            $('.show_if_none_selected, .show_if_one_selected').hide(time);
        }
    }
    hideShowControlButtons(0);

    function attachTableHandlers() {
        var content = $('tbody').selectable({
            filter: 'tr',
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
        $('tbody tr').dblclick(openEditDialog);
  
        $("abbr.timeago").timeago(); 
        $('tr.just_updated').removeClass('just_updated', 1000);
    }
    attachTableHandlers();

    /* Reload Table */
    function reloadTable(){
        $('#admin_table_container').load(currentPath + '/table', function(){
            attachTableHandlers();
            postTableReload();
            stopLoading();
            hideShowControlButtons();
            $('.ui-widget-overlay').height($(document).height());
        });
        startLoading();
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
            $('#edit_form input:submit').click();
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


    $('#delete_dialog').dialog({
      autoOpen: false,
      resizable: false,
      modal: true,
      width: 400,
      dialogClass: 'delete_dialog',
      buttons : {
        "Delete" : function(){
          var requests_remaining = $('tbody tr.ui-selected').length;
          var failures = 0;
          if (requests_remaining > 0) {
            $('.delete_dialog .ui-dialog-buttonset .loading_spinner').show();
            $('.delete_dialog .ui-dialog-buttonset button').button('disable');
            $('tbody tr.ui-selected').each(function(){
              var id = $(this).data('entity').id;

              $.ajax({
                // type: 'DELETE',
                type: 'POST',
                data: {_method: 'DELETE'},
                url: currentPath + '/' + id,
                dataType: 'json'
              }).fail(function(){
                failures += 1;
              }).always(function(result){
                requests_remaining -= 1;
                if (requests_remaining === 0) {
                  $('.delete_dialog .ui-dialog-buttonset .loading_spinner').hide();
                  $('.delete_dialog .ui-dialog-buttonset button').button('enable');
                  if (failures === 0) {
                    $('#delete_dialog').dialog('close');
                    $('table tr.ui-selected').addClass('just_deleted');
                    reloadTable();
                  }
                }
              });

            });
          } else {
            $(this).dialog("close");
          }
        },
        "Cancel" : function(){
          $(this).dialog("close");
        }
      }
    });

    $('.table_buttons .loading_spinner').clone().prependTo('.ui-dialog-buttonset');

    /* Button Handlers */
    function openCreateDialog(){
        $('.edit_dialog .ui-dialog-buttonset .submit_button .ui-button-text').html('Create');
        $('#edit_dialog').dialog('open').dialog({ title: "Add " + entitySingular.capitalize() });
        $('#edit_form').attr('method', 'POST');
        $('#edit_form input[name="_method"]').val('POST');
        $('#edit_form').attr('action', currentPath);
        $('#edit_form').find('input, textarea').not(':button, :submit, :reset, :hidden, :radio, :checkbox').val(''); 
        $('#edit_form').find('.error').remove();
        initCreateForm();
    }
    $('button.create').click(openCreateDialog);

    function openEditDialog(){
        var selection = $('tbody tr.ui-selected');
        if (selection.length != 1) return;
        var entity = selection.first().data('entity');
        $('.edit_dialog .ui-dialog-buttonset .submit_button .ui-button-text').html('Save Changes');
        $('#edit_dialog').dialog('open').dialog({ title: "Edit " + entitySingular.capitalize() });
        // $('#edit_form').attr('method', 'PUT');
        $('#edit_form input[name="_method"]').val('PUT');
        $('#edit_form').attr('method', 'POST');
        $('#edit_form').find('.error').remove();
        $('#edit_form').attr('action', currentPath + '/' + entity.id);
        $('#edit_form').find('input, select, textarea').not(':button, :submit, :reset, :hidden, :radio, :checkbox').each(function(){
            var name_array = $(this).attr('name').split('[');
            name_array.splice(0,1);
            name_array = $.map(name_array, function(value, i) {
                return value.split(']')[0];
            });
            var main_name = name_array[0];
            if (entity.hasOwnProperty(main_name)) {
              var value = entity[main_name];
              if (value === false) value = 0;
              else if (value === true) value = 1;
              if (name_array[1] == "date") value = moment(value).format('DD/MM/YYYY');
              if (name_array[1] == "time") value = moment(value).format('h:mm a');
              if (typeof value == 'object') {
                if ($(this).prop("tagName") == "SELECT") value = value.id;
                else value = value.name;
              }
              $(this).val(value);
            } else {
              $(this).val('');
            }
        });
        initEditForm();
    };
    $('button.edit').click(openEditDialog);

    $('button.refresh').click(reloadTable); // Refresh button

    $('button.unselectall, #above_footer, #signupsfilter a').click(function(){ // Unselect all button/area
      $('tbody tr').removeClass('ui-selected')
      hideShowControlButtons();
    });
    $('.table_buttons button').click(function(e){
      e.stopPropagation();
    });

    $('button.delete').click(function(){ // Delete button
      $('#delete_dialog').dialog('open');
    })

    /* Form submit */
    $('.ui-dialog form').submit(function () {
        var form_element = $(this);
        var containing_dialog = form_element.closest('.ui-dialog-content');
        var dialog_buttonpane = containing_dialog.nextAll('.ui-dialog-buttonpane');
        if (form_element.find('input:file').length !== 0) {      // if files, we have to use multipart/form-data; doesn't play nicely with PUT
            var formData = new FormData(form_element[0]);
            var contentType = false;
            var processData = false;
        } else {                                            // if no files, use urlencoded data; can use either POST or PUT
            var formData = form_element.serialize();
            var contentType = 'application/x-www-form-urlencoded; charset=UTF-8';
            var processData = true;
        }
        $.ajax({
            type: form_element.attr('method'),
            url: form_element.attr('action'),
            data: formData,
            dataType: 'json', 
            cache: false,
            contentType: contentType,
            processData: processData, 
            complete: function (result) {}
        }).always(function(){
            dialog_buttonpane.find('.loading_spinner').hide();
            dialog_buttonpane.find('button').button('enable');
        }).done(function(result){
            if (result.status == "success"){
                containing_dialog.dialog('close');
                reloadTable();
            } else {
                if (result.status == "partial") {
                    reloadTable();
                }
                for (var key in result.errors) {
                    var error_element = $('<div/>',{
                      'class' : 'error',
                      'text' :  (function(){
                        var val = result.errors[key];
                        // console.log(typeof val.first);
                        if (typeof val === 'string') {
                          return val;
                        } else if (typeof val === 'object' && typeof val.first === 'object') {
                          return val.first[0];
                        } else {
                          return val[0];
                        }
                      })()
                    });
      
                    var bad_input_element = form_element.find('*[name*="[' + key + ']"]');
                    if (isNaN(key) && bad_input_element.length !== 0) {
                        bad_input_element.before(error_element);
                    } else {
                        if (isNaN(key)) { // if key is named but matching input can't be found
                            error_element.html(key + ': ' + error_element.html());
                        }
                        error_element.addClass('global_error').appendTo(form_element);
                    }
                }
            }
        });
        dialog_buttonpane.find('.loading_spinner').show();
        dialog_buttonpane.find('button').button('disable');
        form_element.find('.error').remove();

        return false; 
    });

})();
