(function(){var t=$(".doceditor");var e=$("#bundles_basedir").attr("href");var i=$("#main_stylesheet").attr("href");var n="sourcedialog,image2,entities,autogrow";["webkit-span-fix","heading-button"].forEach(function(t){CKEDITOR.plugins.addExternal(t,e+"/icsemembers/lib/ckeditor/plugins/"+t+"/","plugin.js");n+=","+t});t.each(function(){var t="Sourcedialog";var e="";var o="";if($(this).is("textarea")){t="Source";e=i;o="icseeditorcontent"}$(this).ckeditor(function(){},{extraPlugins:n,customConfig:"",extraAllowedContent:"*[id](*)",toolbar:[{name:"styles",items:["HeadingButton"]},{name:"basicstyles",items:["Bold","Italic","-","RemoveFormat"]},{name:"editing",items:["Scayt","-",t]},"/",{name:"styles2",items:["Format"]},{name:"insert",items:["NumberedList","BulletedList","-","Link","Image","HorizontalRule"]},{name:"tools",items:["ShowBlocks","-","About"]}],image2_alignClasses:["image-left","image-center","image-right"],image2_captionedClass:"image-captioned",entities_processNumerical:true,contentsCss:e,bodyClass:o})});function o(t){this.ckeditor=t.ckeditorGet()}o.prototype.getContent=function(){return this.ckeditor.getData()};o.prototype.setContent=function(t){return this.ckeditor.setData(t)};o.prototype.onChange=function(t){return this.ckeditor.on("change",t)};$.fn.icseDocEditor=function(){return new o(this)}})();
(function(){if(typeof initCreateForm!="function"){window.initCreateForm=function(){}}if(typeof initEditForm!="function"){window.initEditForm=function(){}}if(typeof postTableReload!="function"){window.postTableReload=function(){}}function e(e){e.wrap("<form>").parent()[0].reset();e.unwrap()}function t(e){if(e.data("label")){var t=$("<span>",{"class":"fileinput-button-wrapper "+e.attr("class")});t.append($("<span>",{text:e.data("label")}));t.insertAfter(e);t.button();e.detach().appendTo(t)}}$("button").button();$('input[type="file"]').each(function(){t($(this))});$("input.time").timepicker({timeFormat:"g:i a"});$("input.date").datepicker({dateFormat:"dd/mm/yy"});$("#edit_form input:submit, #import_csv_form input:submit").hide();$("form").each(function(){if($(this).find("input").first().is(".date, .time")){$(this).before('<input type="text" style="width: 0; height: 0; top: -100px; position: absolute;">')}});var n=$("#edit_form");n.find("input:submit").before('<input type=hidden name="_method" value="POST" >');function i(e){e.select2({allowClear:true})}i($(".entity-select"));var a=$("html");a.on("dragenter",function(e){e.preventDefault();e.stopPropagation()});a.on("dragover",function(e){e.preventDefault();e.stopPropagation()});a.on("dragleave",function(e){e.preventDefault();e.stopPropagation()});a.on("drop",function(e){e.preventDefault();e.stopPropagation()});if(typeof fos!="undefined"&&typeof fos.Router!="undefined"){fos.Router.prototype.generateIgnoringExtras=function(e,t,n){var i=this.generate(e,t,n);return i.replace(/\?.*$/,"")}}var o;var r;(function(){var e=0;o=function(){e+=1;$(".entity_main_buttons .loading_spinner").show()};r=function(){e-=1;if(e==0){$(".entity_main_buttons .loading_spinner").hide()}}})();$.fn.isAnyEntityInstanceSelected=function(){return $(this).hasClass("ui-selected")};$.fn.isExactlyOneEntityInstanceSelected=function(){return $(this).filter(".ui-selected").length==1};function s(e){if(typeof e==="undefined")e=200;var t=$(".entity_instance_list .instance");if(!t.isAnyEntityInstanceSelected()){$(".show_if_none_selected").show(e);$(".show_if_one_selected, .show_if_any_selected").hide(e)}else if(t.isExactlyOneEntityInstanceSelected()){$(".show_if_one_selected, .show_if_any_selected").show(e);$(".show_if_none_selected").hide(e)}else{$(".show_if_any_selected").show(e);$(".show_if_none_selected, .show_if_one_selected").hide(e)}}s(0);function l(){var e=$(".entity_instance_list");var t=e.selectable({filter:".instance",stop:s});var n=t.data("uiSelectable")["_mouseStart"];t.data("uiSelectable")["_mouseStart"]=function(e){n.call(this,e);this.helper.css({top:-1,left:-1})};e.find(".instance").dblclick(_);e.find("abbr.timeago").timeago();e.find(".instance.just_updated").removeClass("just_updated",1e3);var i=e.filter(".waterfall");i.masonry();i.find(".instance").each(function(){var e=$(this).find("img");e.addClass("not-loaded");e.imagesLoaded().always(function(){e.removeClass("not-loaded")})})}l();window.onNavColumnToggle=function(){$(".entity_instance_list").filter(".waterfall").masonry()};function d(){var e=$.Deferred();$("#entity_instance_list_container").load(currentPath+"/list",function(){l();postTableReload();r();s();$(".ui-widget-overlay").height($(document).height());e.resolve()});o();return e.promise()}function c(e){return $(".entity_instance_list .instance").filter(function(){return $(this).data("entity").id===e})}$.widget("ui.dialog",$.ui.dialog,{_allowInteraction:function(e){return!!$(e.target).closest('[class*="cke"]').length||this._super(e)}});var f=$("#edit_dialog");f.dialog({autoOpen:false,modal:true,width:500,minWidth:500,height:500,dialogClass:"edit_dialog",buttons:[{text:"",click:function(){var e=$("#edit_form");e.data("reopen",false);e.find("input:submit").click()},"class":"submit_button"},{text:"Cancel",click:function(){$(this).dialog("close")},"class":"cancel_button"}],resizeStop:function(e,t){f.find(".doceditor").ckeditor().editor.execCommand("autogrow")}});function u(){F().done(function(){$(".delete_dialog .ui-dialog-buttonset .loading_spinner").show();$(".delete_dialog .ui-dialog-buttonset button").button("disable");var e=false;var t=$(".entity_instance_list .instance.ui-selected").map(function(){var t=$(this).data("entity").id;var n=$.Deferred();$.ajax({type:"POST",data:{_method:"DELETE",fb:C},url:currentPath+"/"+t,dataType:"json"}).always(function(t){if(t.status!="success"){e=true}n.resolve()});return n.promise()});$.when.apply(null,t).done(function(){$(".delete_dialog .ui-dialog-buttonset .loading_spinner").hide();$(".delete_dialog .ui-dialog-buttonset button").button("enable");if(!e){$("#delete_dialog").dialog("close");$(".entity_instance_list .instance.ui-selected").addClass("just_deleted");d()}})})}$("#delete_dialog").dialog({autoOpen:false,resizable:false,modal:true,width:400,dialogClass:"delete_dialog",buttons:{Delete:u,Cancel:function(){$(this).dialog("close")}}});$(".entity_main_buttons .loading_spinner").clone().prependTo(".ui-dialog-buttonset");var p=n.find("input, select, textarea").not(":button, :submit, :reset").not('input[name="_method"], input[name$="[_token]"]');function h(){$(".edit_dialog .ui-dialog-buttonset .submit_button .ui-button-text").html("Create");$("#edit_dialog").dialog("open").dialog({title:"Add "+entitySingularTitle});n.attr("method","POST");n.find('input[name="_method"]').val("POST");n.attr("action",currentPath);p.not(":radio, :checkbox").val("");n.find("select").trigger("change");n.find("input").filter(":radio, :checkbox").prop("checked",false);n.find(".error").remove();n.find(".show_if_create").show();n.find(".show_if_edit").hide();n.data("form_mode","create");initCreateForm(n)}$("button.create").click(h);function _(){var e=$(".entity_instance_list .instance.ui-selected");if(e.length!=1)return;var t=e.first().data("entity");$(".edit_dialog .ui-dialog-buttonset .submit_button .ui-button-text").html("Save Changes");$("#edit_dialog").dialog("open").dialog({title:"Edit "+entitySingularTitle});n.find('input[name="_method"]').val("PUT");n.attr("method","POST");n.find(".error").remove();n.find(".show_if_edit").show();n.find(".show_if_create").hide();n.data("form_mode","edit");n.attr("action",currentPath+"/"+t.id);p.each(function(){var e=$(this).attr("name");if(typeof e==="undefined")return;var n=e.split("[");n.splice(0,1);n=$.map(n,function(e){return e.split("]")[0]});var i=n[0];var a="";if(t.hasOwnProperty(i)){a=t[i];if($(this).hasClass("date"))a=moment(parseInt(a)).format("DD/MM/YYYY");else if($(this).hasClass("time")){a=moment(parseInt(a)).format("h:mm a");if(i=="starts_at"&&t["is_start_time_known"]===false)a=""}else if(typeof a=="object"&&a!==null){if(a instanceof Array)a=null;else if($(this).is("select"))a=a.id;else a=a.name}}if($(this).is(":radio, :checkbox")){if(typeof a==="boolean")$(this).prop("checked",a)}else{if(typeof a==="boolean")a=a?1:0;$(this).val(a);if($(this).is("select"))$(this).trigger("change")}});initEditForm(n,t)}$("button.edit").click(_);$('input.create[type="file"]').change(function(){var t=$(this).data("change-handler");t(this.files);e($(this))});$("button.refresh").click(d);$("button.unselectall, #above_footer, #signupsfilter a").click(function(){$(".entity_instance_list .instance").removeClass("ui-selected");s()});$(".entity_main_buttons button").click(function(e){e.stopPropagation()});$("button.submit_and_reopen").click(function(){var e=$(this).closest("form");e.data("reopen",true);e.find("input:submit").click();return false});$("button.delete").click(function(){$("#delete_dialog").dialog("open")});var v=function O(e,t,n){for(var i in n)if(n.hasOwnProperty(i)){var a=n[i];var o=t+"["+i+"]";if(typeof a=="string"){var r=$("<div>",{"class":"error",text:a});var s=e.find('*[name="'+o+'"]');if(s.length==0){s=e.find('*[name="'+t+'"]')}if(s.length==0){if(isNaN(i))r.text(i+": "+r.text());r.addClass("global_error").appendTo(e)}else{s.before(r)}}else if(typeof a=="object"){O(e,o,a)}}};$(".ui-dialog form").submit(function(){var e=$(this);var t=e.closest(".ui-dialog-content");var n=t.nextAll(".ui-dialog-buttonpane");var i=n.add(e).find("button");n.find(".loading_spinner").show();i.each(function(){$(this).data("was_disabled",$(this).prop("disabled"))});i.button("disable");e.find(".error").remove();var a,o,r;if(e.find("input:file").length!==0){a=new FormData(e[0]);o=false;r=false}else{a=e.serialize();o="application/x-www-form-urlencoded; charset=UTF-8";r=true}$.ajax({type:e.attr("method"),url:e.attr("action"),data:a,dataType:"json",cache:false,contentType:o,processData:r}).always(function(){n.find(".loading_spinner").hide();i.each(function(){if(!$(this).data("was_disabled")){$(this).button("enable")}})}).done(function(n){if(n.status=="success"){t.dialog("close");var i=d();if(e.data("reopen")===true){i.done(function(){var e=c(n.entity.id);e.addClass("ui-selected");s();$("button.edit").click()})}}else{if(n.status=="partial"){d()}v(e,"form",n.errors)}}).always(function(){e.data("reopen",null)});return false});function m(e,t){t.split(".").forEach(function(t){e=e[t]});return e}function g(e){e.find("li").each(function(){$(this).find("input.sort_index").val($(this).index())})}var b=function(e){var t=e.find("li").map(function(){return $(this).find("input.sort_index").val()});return t.length==0?-1:Math.max.apply(null,t)};n.find(".sortable-list").each(function(){var e=$(this);e.sortable({placeholder:"drag-placeholder",cancel:"a,.a-button,input",update:function(){g(e)}})});var y=function(e,t,n,i){i=i?i:function(){};var a=e.find(t).detach();a.find("input").removeAttr("id");a.find(".a-button.delete").click(function(){$(this).closest(t).addTransitionClass("hidden").done(function(){$(this).remove()});return false});return function(e){var t=a.clone(true);n.forEach(function(n){var i=t.find("."+n.split(".").join("_"));var a=m(e,n);if(i.is("input")){i.val(a);i.attr("name",i.attr("name").replace("__ID__",e.id))}else if(i.is("span")){i.html(a)}});i(t,e);return t}};(function(){if(n.hasClass("event")){var e=n.find("ul#performances");var t=y(e,"li.performance",["piece.id","piece.full_name","sort_index"]);var i=n.find(".performance_adder");var a=i.filter("select");var o=i.filter("div");var r=0;a.change(function(){var n=$(this).select2("data");if(n!==null){var i=n.id;var a=n.text;if(i!==""){$(this).select2("val","");o.hide();o.slideDown(300);var s=t({id:"new_"+r++,piece:{id:i,full_name:a},sort_index:b(e)+1});e.append(s)}}});window.initCreateForm=function(){e.empty();r=0};window.initEditForm=function(n,i){r=0;var a=i.performances;e.empty();a.forEach(function(n){var i=t(n);e.append(i)})}}})();(function(){if(n.hasClass("piece_of_music")){var t=n.find("#add_files_button");var i=$("#piece_of_music_add_new_part_form");var a=n.find("ul#practice_parts");i.find('input[name="form[file]"]').remove();var o;(function(){var e=a.find("li.uploading-part").detach();o=function(){var t=e.clone();t.find(".progress-bar").progressbar({value:false});return t}})();var r=y(a,"li.part",["instrument","sort_index"],function(e,t){e.find("a.open").attr("href",Routing.generateIgnoringExtras("IcsePublicBundle_resource",t))});var s=function(e,t){var n=t.find("button");var s=t.find(".loading_spinner");var l=b(a)+1;i.find('input[name="form[sort_index]"]').val(l);var c=o();c.find(".name").text(e.name);var f=c.find(".progress-bar");c.find("input.sort_index").val(l);c.hide();a.append(c);c.show(500);var u=new FormData(i[0]);u.append("form[file]",e);n.button("disable");s.show();var p=false;$.ajax({url:i.attr("action").replace("__ID__",a.data("piece_id")),type:"POST",data:u,dataType:"json",processData:false,contentType:false,xhr:function(){xhr=$.ajaxSettings.xhr();if(xhr.upload){xhr.upload.addEventListener("progress",function(e){if(e.lengthComputable){f.progressbar({value:e.loaded,max:e.total})}},false)}return xhr}}).done(function(e){if(e.status=="success"){var t=r(e.entity);c.replaceWith(t);p=true}}).always(function(){if(!p)c.remove();if(a.find(".uploading-part").length===0){n.button("enable");s.hide();g(a);d()}})};var l=function(e){var t=n.closest(".ui-dialog-content").nextAll(".ui-dialog-buttonpane");for(var i=0;i<e.length;i++){s(e[i],t)}};t.change(function(){l(this.files);e($(this))});var c=n.find("#practice_parts_section .drop_mask");c.data("drop-handler",l);window.initCreateForm=function(){a.empty();c.data("enabled",false)};window.initEditForm=function(e,t){c.data("enabled",true);a.data("piece_id",t.id);var n=t.practice_parts;a.empty();n.forEach(function(e){var t=r(e);a.append(t)})}}})();(function(){var e=$("#edit_form.image");if(e.hasClass("image")){var t=$("form#new_image");t.find('input[name="form[file]"]').remove();var n;(function(){var e=$("#image_prototype").detach().removeProp("hidden");n=function(){var t=e.clone();t.find(".progress-bar").progressbar({value:false});return t}})();var i=function(e){if(!e.type.match("image.*")){return}o();var i=$(".entity_instance_list");var a=n();var s=a.find(".progress-bar");i.prepend(a);var l=new FileReader;l.onloadend=function(e){a.find("img").attr("src",e.target.result);i.masonry("prepended",a);r()};l.readAsDataURL(e);var c=new FormData(t[0]);c.append("form[file]",e);$.ajax({url:t.attr("action"),type:t.attr("method"),data:c,dataType:"json",processData:false,contentType:false,xhr:function(){xhr=$.ajaxSettings.xhr();if(xhr.upload){xhr.upload.addEventListener("progress",function(e){if(e.lengthComputable){s.progressbar({value:e.loaded,max:e.total})}},false)}return xhr}}).always(function(){a.addClass("instance").removeClass("uploading-instance");if($(".entity_instance_list").find(".uploading-instance").length===0){console.log("reload table");d()}})};var a=function(e){for(var t=0;t<e.length;t++){i(e[t])}};$("section .drop_mask").data("drop-handler",a);$('input.create[type="file"]').data("change-handler",a)}})();(function(){var e=$(".drop_mask");e.each(function(){var e=$(this);var t=e.parent();var n=e.hasClass("height_fix");t.on("dragenter",function(){if(e.data("enabled")===true){t.addClass("draginside");if(n)e.height(t.outerHeight())}});e.on("dragleave",function(){t.removeClass("draginside")});e.on("drop",function(n){t.removeClass("draginside");var i=n.originalEvent.dataTransfer.files;var a=e.data("drop-handler");if(typeof a=="function")a(i)})})})();function w(e){var t=$.Deferred();$("<div>").html(e).dialog({close:function(){t.resolve()},resizable:false,closeOnEscape:false,buttons:{Okay:function(){$(this).dialog("close")}}});return t.promise()}function x(e){var t=$.Deferred();var n=$("<div>").html(e).dialog({resizable:false,closeOnEscape:false,open:function(){$(this).parent().find(".ui-dialog-titlebar-close").hide()},modal:true});t.always(function(){n.dialog("close")});return t}var k=false;var C=null;var T=["create_event","manage_pages"];function E(e){k=e.status==="connected"}if(enable_social_net){$.ajaxSetup({cache:true});$.getScript("//connect.facebook.net/en_UK/all.js",function(){FB.init({appId:facebookAppId,status:true});FB.Event.subscribe("auth.authResponseChange",E);$("button.social_sync").button("option","disabled",false)})}function D(){var e=$.Deferred();FB.login(function(t){E(t);if(k){e.resolve()}else{e.reject()}},{scope:T.join(",")});return e.promise()}function S(e,t){D().done(function(){e(t)}).fail(function(){t.reject()})}function j(e){var t=$.Deferred();console.log("Already connected:",k);if(k){e($.Deferred()).done(function(){console.log("Success first time");t.resolve()}).fail(function(){w("Click to reconnect to Facebook.").always(function(){S(e,t)})})}else{S(e,t)}return t.promise()}function P(){if(C!==null){return $.when()}else{return j(function(e){FB.api("/me/accounts/",function(t){var n=false;if($.isArray(t.data))for(var i=0;i<t.data.length;i++){var a=t.data[i];if(a.id===facebookPageId){console.log(a.name,a.access_token);C=a.access_token;n=true;break}}n?e.resolve():e.reject()});return e.promise()})}}function F(){if(enable_social_net){return $.when(P())}else{return $.when()}}$("button.social_sync").button().click(function(){F().done(function(){var e=x("Synchronising changes to social networks...");var t=$(".entity_instance_list .instance.ui-selected").map(function(){var e=$(this).data("entity").id;var t=$.Deferred();$.ajax({type:"POST",url:currentPath+"/"+e+"?"+$.param({op:"socialnetsync"}),data:{fb:C},dataType:"json"}).always(function(e){if(e.status=="fail"){C=null}t.resolve()});return t.promise()});$.when.apply(null,t).done(function(){e.resolve();d()})})})})();