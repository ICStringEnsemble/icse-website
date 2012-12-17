<?php

/* IcsePublicBundle:SignUp:join.html.twig */
class __TwigTemplate_b4c82f4f716210dde7ca85d9ba20a63b extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return $this->env->resolveTemplate((((array_key_exists("freshers", $context) && (isset($context["freshers"]) ? $context["freshers"] : null))) ? ("IcsePublicBundle:SignUp:freshers_template.html.twig") : ("IcsePublicBundle:Default:template.html.twig")));
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 2
        $context["currentPage"] = "join";
        $this->getParent($context)->display($context, array_merge($this->blocks, $blocks));
    }

    // line 4
    public function block_title($context, array $blocks = array())
    {
        echo " - Join Us";
    }

    // line 6
    public function block_content($context, array $blocks = array())
    {
        // line 7
        echo "
";
        // line 8
        echo " 
";
        // line 9
        echo (isset($context["join_intro"]) ? $context["join_intro"] : null);
        echo "
";
        // line 10
        echo " 

<form>
  <div class=\"global_errors\">
    ";
        // line 14
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock((isset($context["form"]) ? $context["form"] : null), 'errors');
        echo " 
  </div>
</form>

<form id=\"username_or_email_form\" style=\"display: none\">
  <label for=\"username_or_email_field\">Enter your Imperial username</label>
  <input id=\"username_or_email_field\" type=\"text\" placeholder=\"Eg. jfs10\"/>
  <input type=\"submit\" value=\"Continue\"/>
  <div id=\"username_or_email_error\" style=\"display: none\">
    Not a valid Imperial Username or e-mail address.
  </div>
</form>

<form action=\"\" method=\"post\" ";
        // line 27
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock((isset($context["form"]) ? $context["form"] : null), 'enctype');
        echo " >
  <div class=\"show_if_email show_if_username\">
    ";
        // line 29
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : null), "first_name"), 'row');
        echo " 
    ";
        // line 30
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : null), "last_name"), 'row');
        echo " 
  </div>
  <div class=\"never_show\">
    ";
        // line 33
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : null), "login"), 'row');
        echo "
  </div> 
  <div class=\"show_if_username\">
    ";
        // line 36
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : null), "email"), 'row');
        echo " 
    ";
        // line 37
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : null), "department"), 'row');
        echo " 
  </div>
  <div class=\"show_if_email show_if_username radio\">
    ";
        // line 40
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : null), "player"), 'row');
        echo " 
  </div>
  <div class=\"show_if_player\">
    <div class=\"radio\">
      ";
        // line 44
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : null), "instrument"), 'row');
        echo " 
    </div>
    <div id=\"other_instrument\">
      ";
        // line 47
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : null), "other_instrument"), 'row');
        echo " 
    </div>
    ";
        // line 49
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : null), "standard"), 'row');
        echo " 
  </div>
  ";
        // line 51
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock((isset($context["form"]) ? $context["form"] : null), 'rest');
        echo " 
  <input class=\"show_if_email show_if_username\" type=\"submit\" value=\"Submit\" />
</form>

<script>
  \$('#other_instrument').remove().appendTo('#form_instrument');


  var username_or_email = \"";
        // line 59
        echo twig_escape_filter($this->env, (isset($context["username_or_email"]) ? $context["username_or_email"] : null), "html", null, true);
        echo "\";
  slideTime = 300;
  isReset = true;
  player = false;
  \$('#username_or_email_form').show();
  \$('.show_if_email, .show_if_username, .never_show, .show_if_player').hide();
  \$('#form_other_instrument').attr('placeholder', 'Please specify');
  \$('#form_other_instrument').prop('disabled', true);

  var query_username_or_email = function(slideTime){
    if (isReset){
      var input = \$('#username_or_email_field').val()
      \$.getJSON(\"";
        // line 71
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("IcsePublicBundle_query_username"), "html", null, true);
        echo "\",
                {input: input},
                function(data){
                  if (data['type'] == 'email'){
                    \$('.show_if_email').slideDown(slideTime);
                    \$('#form_email').val(input);
                    \$('#form_login').val(\"\");
                    \$('#form_department').val(\"\");
                  } else if (data['type'] == 'username'){
                    \$('.show_if_username').slideDown(slideTime);
                    \$('#form_first_name').val(data['first_name']);
                    \$('#form_last_name').val(data['last_name']);
                    \$('#form_email').val(data['email']);
                    \$('#form_login').val(input);
                    \$('#form_department').val(data['department']);
                  } else {
                    \$('#username_or_email_error').slideDown();
                  }
                  if ((data['type'] == 'username' || data['type'] == 'email') && player == true){
                    \$('.show_if_player').slideDown(slideTime);
                  }
                });
      isReset = false;
    }
  }

  if (username_or_email){
    console.log('yay');
    \$('#username_or_email_field').val(username_or_email);
  }
  if (\$('#username_or_email_field').val() != \"\")  
    query_username_or_email(0);
  \$('#username_or_email_form').submit(function(e){e.preventDefault();query_username_or_email(slideTime);});

  var hideAll = function(slideTime){
    \$('.show_if_email, .show_if_username, .show_if_player, #username_or_email_error').slideUp(slideTime);
    isReset = true;
  };

  \$('#username_or_email_field').keyup(function(e){
    if(e.keyCode < 13 || e.keyCode == 32 || e.keyCode > 40) hideAll();
  });
  \$('#username_or_email_field').bind('paste', hideAll);

  var enableDisableOtherInstrument = function(){
    if (\$('#form_instrument input[value=\"other\"]').is(':checked')){
      \$('#form_other_instrument').prop('disabled', false);
      \$('#form_other_instrument').prop('required', true);
    } else {
      \$('#form_other_instrument').prop('disabled', true);
      \$('#form_other_instrument').prop('required', false);
    }
  };
  enableDisableOtherInstrument();
  \$('#form_instrument').change(enableDisableOtherInstrument);

  var showHidePlayer = function(slideTime){
    if (\$('#form_player input[value=\"1\"]').is(':checked')){
      \$('input[name=\"form[instrument]\"]').prop('required', true);
      \$('#form_standard').prop('required', true);
      enableDisableOtherInstrument();
      \$('.show_if_player').slideDown(slideTime);
      player = true;
    } else {
      \$('.show_if_player').slideUp(slideTime);
      \$('input[name=\"form[instrument]\"]').prop('required', false);
      \$('#form_standard').prop('required', false);
      \$('#form_other_instrument').prop('required', false);
      player = false;
    }
  };
  showHidePlayer(0);
  \$('#form_player').change(slideTime, showHidePlayer);

</script>

";
    }

    public function getTemplateName()
    {
        return "IcsePublicBundle:SignUp:join.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  101 => 40,  278 => 111,  269 => 108,  266 => 107,  264 => 106,  253 => 97,  244 => 95,  240 => 94,  217 => 73,  208 => 71,  195 => 67,  192 => 66,  140 => 49,  134 => 46,  124 => 51,  241 => 2,  228 => 1,  197 => 72,  190 => 65,  179 => 64,  175 => 61,  169 => 62,  107 => 34,  53 => 12,  52 => 14,  40 => 9,  301 => 100,  295 => 96,  292 => 95,  289 => 94,  287 => 93,  282 => 118,  276 => 86,  273 => 109,  270 => 84,  268 => 83,  263 => 80,  249 => 79,  245 => 77,  230 => 75,  222 => 77,  220 => 72,  215 => 70,  211 => 72,  204 => 66,  198 => 63,  185 => 61,  183 => 60,  177 => 58,  160 => 60,  149 => 52,  123 => 47,  120 => 46,  82 => 21,  38 => 7,  150 => 71,  144 => 50,  142 => 50,  129 => 45,  125 => 44,  117 => 41,  112 => 40,  106 => 35,  87 => 28,  70 => 27,  67 => 19,  61 => 17,  47 => 9,  91 => 36,  84 => 19,  74 => 19,  66 => 20,  55 => 13,  32 => 4,  28 => 2,  46 => 8,  225 => 96,  216 => 90,  212 => 88,  205 => 74,  201 => 69,  196 => 80,  194 => 71,  191 => 78,  189 => 77,  180 => 72,  172 => 67,  159 => 61,  154 => 54,  147 => 53,  121 => 42,  118 => 36,  114 => 47,  104 => 36,  100 => 30,  78 => 20,  75 => 29,  71 => 18,  63 => 18,  34 => 11,  202 => 3,  200 => 2,  186 => 63,  181 => 54,  176 => 55,  174 => 54,  168 => 59,  163 => 57,  158 => 58,  155 => 25,  139 => 22,  135 => 59,  132 => 48,  127 => 45,  109 => 39,  103 => 34,  90 => 26,  86 => 25,  65 => 16,  49 => 10,  37 => 6,  43 => 7,  29 => 4,  44 => 9,  25 => 4,  105 => 31,  96 => 21,  93 => 20,  83 => 18,  76 => 22,  72 => 28,  68 => 12,  58 => 16,  50 => 13,  41 => 8,  27 => 4,  24 => 2,  22 => 2,  19 => 1,  94 => 27,  88 => 24,  79 => 30,  59 => 15,  35 => 6,  31 => 4,  26 => 2,  21 => 2,  184 => 66,  178 => 71,  171 => 60,  165 => 58,  162 => 57,  157 => 55,  153 => 54,  151 => 52,  143 => 59,  138 => 47,  136 => 50,  133 => 43,  130 => 47,  122 => 37,  119 => 49,  116 => 60,  111 => 57,  108 => 44,  102 => 30,  98 => 28,  95 => 37,  92 => 28,  89 => 19,  85 => 33,  81 => 25,  73 => 19,  64 => 15,  60 => 23,  57 => 14,  54 => 14,  51 => 9,  48 => 10,  45 => 9,  42 => 7,  39 => 6,  36 => 8,  33 => 4,  30 => 7,);
    }
}
