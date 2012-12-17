<?php

/* IcseMembersBundle:Admin:images.html.twig */
class __TwigTemplate_58f349efd6cb42f528bc58d89a7c1334 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("IcseMembersBundle:Default:template.html.twig");

        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "IcseMembersBundle:Default:template.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_title($context, array $blocks = array())
    {
        echo " - Image Uploads";
    }

    // line 5
    public function block_content($context, array $blocks = array())
    {
        // line 6
        echo "
<h1>Image Uploads</h1>

<button id=new_image_button disabled=disabled type=button>New Images</button>

<div id=new_image_dialog title=\"Add New Images\" hidden=hidden>
  <form method=\"post\" enctype=\"multipart/form-data\" action=";
        // line 12
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("IcseMembersBundle_receive_upload"), "html", null, true);
        echo ">
    <input type=\"file\" name=\"files[]\" id=\"files\" multiple /> 
    ";
        // line 15
        echo "  </form>
  <ul id=\"upload_list\"></ul>
</div>

<script>
  /* Undo fallback */
  \$('#new_image_button').removeAttr('disabled');

  /* New image dialog */
  \$('#new_image_dialog').dialog({
    autoOpen: false,
    modal: true,
    width: 500,
    height: 500
  });
  \$('#new_image_button').button().click(function(){
    \$('#new_image_dialog').dialog('open');
  });

  /* File Uploader */
  var uploadCount = 0;
  var tmp_path = \"";
        // line 36
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("IcsePublicBundle_resource", array("type" => "tmp", "file" => "")), "html", null, true);
        echo "\"+'/';
  \$('#files').change(function () {
    var len = this.files.length;
    for ( var i=0; i < len; i++ ) {
      file = this.files[i];
      if (file.type.match('image.*')) {
        var uploadID = uploadCount++;
        \$('#upload_list').append('<li><div class=upload_item id=upload'+uploadID+' ><progress></progress></div></li>');
        var formData = new FormData();
        if (formData) {
          formData.append(\"files[]\", file);
          \$.ajax({
            url: \"";
        // line 48
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("IcseMembersBundle_receive_upload"), "html", null, true);
        echo "\",
            type: \"POST\",
            data: formData,
            dataType: 'json', 
            processData: false,
            contentType: false,
            success: function (id, filename) {
              return function (res) {
                var image = res[0];
                \$('div#upload'+id+' progress').replaceWith('<div class=thumbnail><img src='+tmp_path+res[0]+'?size=thumb /></div>');
                \$('div#upload'+id).append(
                    '<form action=\"";
        // line 59
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("IcseMembersBundle_confirm_upload"), "html", null, true);
        echo "\" method=\"post\" ";
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock((isset($context["form"]) ? $context["form"] : null), 'enctype');
        echo ">'
                  + '";
        // line 60
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock((isset($context["form"]) ? $context["form"] : null), 'widget');
        echo "'
                  + '<input type=\"button\" value=\"Cancel\" />'
                  + '<input type=\"submit\" value=\"Save\" />'
                  + '<div class=\"info\"></div>'
                  + '</form> '
                );
                \$('div#upload'+id+' input#file_name').val(filename);
                \$('div#upload'+id+' input#file_file').val(res[0]);

                var frm = \$('div#upload'+id+' form');
                
                frm.submit(function () {
                    \$.ajax({
                        type: frm.attr('method'),
                        url: frm.attr('action'),
                        data: frm.serialize(),
                        dataType: 'json', 
                        success: function (result) {
                          if (result == \"success\"){
                            frm.closest('.upload_item').remove(); 
                          }
                        }
                    });
                    \$('div#upload'+id+' input').prop('disabled', true);
                    \$('div#upload'+id+' .info').html('<img class=\"loading_spinner\" src=\"";
        // line 84
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/icsemembers/images/loading.gif"), "html", null, true);
        echo "\" />');

                    return false; 
                });
              };
            }(uploadID, file.name),
            xhr: function (id) {
              return function() {
                xhr = \$.ajaxSettings.xhr();
                if(xhr.upload){
                  xhr.upload.addEventListener('progress', function(e) {
                    if(e.lengthComputable){
                      \$('div#upload'+id+' progress').attr({value:e.loaded,max:e.total});
                    } 
                  } , false);
                }
                return xhr;
              }
            }(uploadID)
          });

        }
      }
    }

  });


</script>

";
    }

    public function getTemplateName()
    {
        return "IcseMembersBundle:Admin:images.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  167 => 81,  148 => 72,  145 => 70,  131 => 59,  113 => 45,  80 => 26,  110 => 43,  99 => 37,  56 => 22,  101 => 40,  278 => 111,  269 => 108,  266 => 107,  264 => 106,  253 => 97,  244 => 95,  240 => 94,  217 => 73,  208 => 71,  195 => 67,  192 => 66,  140 => 49,  134 => 46,  124 => 51,  241 => 2,  228 => 1,  197 => 72,  190 => 79,  179 => 64,  175 => 61,  169 => 62,  107 => 29,  53 => 12,  52 => 14,  40 => 6,  301 => 100,  295 => 96,  292 => 95,  289 => 94,  287 => 93,  282 => 118,  276 => 86,  273 => 109,  270 => 84,  268 => 83,  263 => 80,  249 => 79,  245 => 77,  230 => 75,  222 => 77,  220 => 72,  215 => 70,  211 => 72,  204 => 66,  198 => 63,  185 => 61,  183 => 60,  177 => 58,  160 => 60,  149 => 52,  123 => 47,  120 => 46,  82 => 21,  38 => 6,  150 => 71,  144 => 50,  142 => 50,  129 => 45,  125 => 44,  117 => 30,  112 => 31,  106 => 35,  87 => 28,  70 => 17,  67 => 33,  61 => 17,  47 => 9,  91 => 36,  84 => 19,  74 => 36,  66 => 20,  55 => 13,  32 => 6,  28 => 2,  46 => 12,  225 => 96,  216 => 90,  212 => 88,  205 => 74,  201 => 69,  196 => 80,  194 => 71,  191 => 78,  189 => 77,  180 => 72,  172 => 67,  159 => 61,  154 => 54,  147 => 53,  121 => 42,  118 => 36,  114 => 47,  104 => 40,  100 => 30,  78 => 20,  75 => 29,  71 => 18,  63 => 18,  34 => 11,  202 => 3,  200 => 2,  186 => 63,  181 => 54,  176 => 55,  174 => 54,  168 => 59,  163 => 57,  158 => 58,  155 => 25,  139 => 22,  135 => 59,  132 => 48,  127 => 45,  109 => 60,  103 => 59,  90 => 26,  86 => 10,  65 => 28,  49 => 9,  37 => 6,  43 => 7,  29 => 3,  44 => 12,  25 => 4,  105 => 31,  96 => 36,  93 => 20,  83 => 27,  76 => 22,  72 => 36,  68 => 12,  58 => 16,  50 => 13,  41 => 8,  27 => 4,  24 => 1,  22 => 2,  19 => 1,  94 => 27,  88 => 31,  79 => 30,  59 => 15,  35 => 5,  31 => 4,  26 => 2,  21 => 2,  184 => 66,  178 => 71,  171 => 60,  165 => 79,  162 => 57,  157 => 74,  153 => 73,  151 => 52,  143 => 59,  138 => 47,  136 => 84,  133 => 43,  130 => 47,  122 => 37,  119 => 49,  116 => 46,  111 => 57,  108 => 44,  102 => 30,  98 => 28,  95 => 37,  92 => 28,  89 => 48,  85 => 33,  81 => 5,  73 => 18,  64 => 13,  60 => 23,  57 => 14,  54 => 14,  51 => 15,  48 => 16,  45 => 8,  42 => 7,  39 => 6,  36 => 5,  33 => 4,  30 => 5,);
    }
}
