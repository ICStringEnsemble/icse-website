$(document).ready(function(){var f=300;var d=null;var h=null;$(".never_show").hide();function c(i){if(h===null){$(".show_if_email, .show_if_username, .show_if_player, #username_or_email_error").slideUp(i)}else{if(h==="email"){$(".show_if_email").slideDown(i)}else{if(h==="username"){$(".show_if_username").slideDown(i)}else{if(h==="error"){$("#username_or_email_error").slideDown()}}}}if(d!==null){$("#username_or_email_lookup").slideDown(i)}if(h==="email"||h==="username"){if(d){$(".show_if_player").slideDown(i)}else{$(".show_if_player").slideUp(i)}}}function b(j){if(h===null){$("#username_or_email_lookup input").prop("disabled",true);var i=$("#username_or_email_field").val();$.getJSON(Routing.generate("IcsePublicBundle_query_username"),{input:i},function(k){if(k.type=="email"){h="email";$("#form_email").val(i);$("#form_login").val("");$("#form_department").val("")}else{if(k.type=="username"){h="username";$("#form_first_name").val(k.first_name);$("#form_last_name").val(k.last_name);$("#form_email").val(k.email);$("#form_login").val(i);$("#form_department").val(k.department)}else{h="error"}}$(".error_list").remove();c(j);$("#username_or_email_lookup input").prop("disabled",false)})}}$("#username_or_email_lookup #lookup_button").click(function(){b(f)});$("#username_or_email_field").keyup(function(i){if(i.keyCode<13||i.keyCode==32||i.keyCode>40){h=null;c(f)}else{if(i.keyCode==13){b(f)}}});$("#username_or_email_field").bind("paste",function(){h=null;c(f)});function g(){if(d){if($('input[name="form[instrument][]"]').is(":checked")){$('input[name="form[instrument][]"]').prop("required",false)}else{$('input[name="form[instrument][]"]').prop("required",true)}}else{$('input[name="form[instrument][]"]').prop("required",false)}}$("#form_instrument").change(g);function a(i){if($('#form_player input[value="1"]').is(":checked")){d=true;$("#form_standard").prop("required",true);e()}else{if($('#form_player input[value="0"]').is(":checked")){d=false;$("#form_standard").prop("required",false);$("#form_other_instrument").prop("required",false)}else{d=null}}g();c(i)}$("#form_player").change(f,a);function e(){if($('#form_instrument input[value="other"]').is(":checked")){$("#form_other_instrument").prop("disabled",false);$("#form_other_instrument").prop("required",true)}else{$("#form_other_instrument").prop("disabled",true);$("#form_other_instrument").prop("required",false)}}$("#form_instrument").change(e);c(0);e();a(0);if($("#username_or_email_field").val()!=""){h=$("#form_login").val()!=""?"username":"email";c(0)}});