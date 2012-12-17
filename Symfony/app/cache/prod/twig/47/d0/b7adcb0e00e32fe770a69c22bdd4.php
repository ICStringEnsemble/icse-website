<?php

/* IcseMembersBundle:AccountSettings:index.html.twig */
class __TwigTemplate_47d0b7adcb0e00e32fe770a69c22bdd4 extends Twig_Template
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
        echo " - Account Settings";
    }

    // line 5
    public function block_content($context, array $blocks = array())
    {
        // line 6
        echo "
<h1>Account Settings</h1>

<h2>Change Email Address</h2>
";
        // line 10
        if ($this->getAttribute((isset($context["ceResponse"]) ? $context["ceResponse"] : null), "success", array(), "array", true, true)) {
            // line 11
            echo "<p class=\"success\">";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["ceResponse"]) ? $context["ceResponse"] : null), "success", array(), "array"), "html", null, true);
            echo "</p>
";
        }
        // line 13
        echo "<p>
Your email address is currently set to ";
        // line 14
        echo twig_escape_filter($this->env, (isset($context["email"]) ? $context["email"] : null), "html", null, true);
        echo ". You can use this address to login to the members
area, and we may also use it for contacting you.
</p>
<form method=\"post\" action=\"\">
  <div>
    ";
        // line 19
        if ($this->getAttribute((isset($context["ceResponse"]) ? $context["ceResponse"] : null), "newemail", array(), "array", true, true)) {
            // line 20
            echo "    <p class=\"error\">";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["ceResponse"]) ? $context["ceResponse"] : null), "newemail", array(), "array"), "html", null, true);
            echo "</p>
    ";
        }
        // line 22
        echo "    <label for=\"form_new_email\">New email address</label>
    <input type=\"email\" id=\"form_new_email\" name=\"new_email\" required=\"required\" />
  </div>
  <input type=\"hidden\" name=\"form_id\" value=\"ce\" /> 
  <input type=\"submit\" value=\"Change Email Address\"/>
</form>

<h2>Change Password</h2>

";
        // line 31
        if ($this->getAttribute((isset($context["cpResponse"]) ? $context["cpResponse"] : null), "success", array(), "array", true, true)) {
            // line 32
            echo "<p class=\"success\">";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["cpResponse"]) ? $context["cpResponse"] : null), "success", array(), "array"), "html", null, true);
            echo "</p>
";
        }
        // line 34
        echo "
<p>
You can either log in using your Imperial College password (in which case we will not store
your password in any way) or you can create a new password to use specifically for the ICSE website.
";
        // line 38
        if (((isset($context["ImperialPasswd"]) ? $context["ImperialPasswd"] : null) == true)) {
            // line 39
            echo "You are currently using your Imperial College password.
";
        } else {
            // line 41
            echo "You are currently using a separate ICSE password.
";
        }
        // line 43
        echo "</p>

<form method=\"post\" action=\"\">
  <div>
    ";
        // line 47
        if ($this->getAttribute((isset($context["cpResponse"]) ? $context["cpResponse"] : null), "oldpass", array(), "array", true, true)) {
            // line 48
            echo "    <p class=\"error\">";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["cpResponse"]) ? $context["cpResponse"] : null), "oldpass", array(), "array"), "html", null, true);
            echo "</p>
    ";
        }
        // line 50
        echo "    <label for=\"form_old_password\">Old Password</label>
    <input type=\"password\" id=\"form_old_password\" name=\"old_password\" required=\"required\" />
  </div>
  <div class=\"radio\">
    ";
        // line 54
        if ($this->getAttribute((isset($context["cpResponse"]) ? $context["cpResponse"] : null), "icsepass", array(), "array", true, true)) {
            // line 55
            echo "    <p class=\"error\">";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["cpResponse"]) ? $context["cpResponse"] : null), "icsepass", array(), "array"), "html", null, true);
            echo "</p>
    ";
        }
        // line 57
        echo "    <label>Create a new ICSE password?</label>
    <div id=\"form_pass_type\">
      <input type=\"radio\" id=\"form_icse_passwd\" name=\"icse_passwd\" required=\"required\" value=\"2\" ";
        // line 59
        if (($this->getAttribute((isset($context["cpResponse"]) ? $context["cpResponse"] : null), "passtype", array(), "array", true, true) && ($this->getAttribute((isset($context["cpResponse"]) ? $context["cpResponse"] : null), "passtype", array(), "array") == 2))) {
            echo "checked=\"yes\"";
        }
        echo "/>
      <label for=\"form_icse_passwd\" class=\" required\">Yes, make a separate password for ICSE.</label>
      <input type=\"radio\" id=\"form_ic_passwd\" name=\"icse_passwd\" required=\"required\" value=\"1\" ";
        // line 61
        if (($this->getAttribute((isset($context["cpResponse"]) ? $context["cpResponse"] : null), "passtype", array(), "array", true, true) && ($this->getAttribute((isset($context["cpResponse"]) ? $context["cpResponse"] : null), "passtype", array(), "array") == 1))) {
            echo "checked=\"yes\"";
        }
        echo "/>
      <label for=\"form_ic_passwd\" class=\" required\">No, I'll use my Imperial College password.</label>
    </div>
  </div>
  <div class=\"show_if_newpass\">
    ";
        // line 66
        if ($this->getAttribute((isset($context["cpResponse"]) ? $context["cpResponse"] : null), "newpass", array(), "array", true, true)) {
            // line 67
            echo "    <p class=\"error\">";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["cpResponse"]) ? $context["cpResponse"] : null), "newpass", array(), "array"), "html", null, true);
            echo "</p>
    ";
        }
        // line 69
        echo "    <label for=\"form_new_password\">Choose a new password</label>
    <input type=\"password\" id=\"form_new_password\" name=\"new_password\" required=\"required\" />
  </div>
  <div class=\"show_if_newpass\">
    <label for=\"form_new_password_again\">Enter your new password again</label>
    <input type=\"password\" id=\"form_new_password_again\" name=\"new_password_again\" required=\"required\" />
  </div>
  <input type=\"hidden\" name=\"form_id\" value=\"cp\" /> 
  <input type=\"submit\" value=\"Change Password\"/>
</form>

<script>
  var enableDisablePasswd = function(slideTime){
    if (\$('#form_icse_passwd').is(':checked')){
      \$('#form_new_password, #form_new_password_again').attr('required', 'required');
      \$('.show_if_newpass').slideDown(slideTime);
    } else {
      \$('#form_new_password, #form_new_password_again').removeAttr('required');
      \$('.show_if_newpass').slideUp(slideTime);
    }
  }
  enableDisablePasswd(0);
  \$('#form_pass_type').change(10, enableDisablePasswd);
</script>

";
    }

    public function getTemplateName()
    {
        return "IcseMembersBundle:AccountSettings:index.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  128 => 55,  126 => 54,  167 => 81,  148 => 72,  145 => 61,  131 => 59,  113 => 45,  80 => 26,  110 => 43,  99 => 37,  56 => 22,  101 => 40,  278 => 111,  269 => 108,  266 => 107,  264 => 106,  253 => 97,  244 => 95,  240 => 94,  217 => 73,  208 => 71,  195 => 67,  192 => 66,  140 => 49,  134 => 57,  124 => 51,  241 => 2,  228 => 1,  197 => 72,  190 => 79,  179 => 64,  175 => 61,  169 => 62,  107 => 29,  53 => 12,  52 => 13,  40 => 6,  301 => 100,  295 => 96,  292 => 95,  289 => 94,  287 => 93,  282 => 118,  276 => 86,  273 => 109,  270 => 84,  268 => 83,  263 => 80,  249 => 79,  245 => 77,  230 => 75,  222 => 77,  220 => 72,  215 => 70,  211 => 72,  204 => 66,  198 => 63,  185 => 61,  183 => 60,  177 => 58,  160 => 60,  149 => 52,  123 => 47,  120 => 50,  82 => 31,  38 => 6,  150 => 71,  144 => 50,  142 => 50,  129 => 45,  125 => 44,  117 => 30,  112 => 47,  106 => 43,  87 => 28,  70 => 17,  67 => 33,  61 => 17,  47 => 9,  91 => 36,  84 => 32,  74 => 36,  66 => 20,  55 => 14,  32 => 6,  28 => 2,  46 => 11,  225 => 96,  216 => 90,  212 => 88,  205 => 74,  201 => 69,  196 => 80,  194 => 71,  191 => 78,  189 => 77,  180 => 72,  172 => 67,  159 => 61,  154 => 54,  147 => 53,  121 => 42,  118 => 36,  114 => 48,  104 => 40,  100 => 30,  78 => 20,  75 => 29,  71 => 22,  63 => 19,  34 => 11,  202 => 3,  200 => 2,  186 => 63,  181 => 54,  176 => 55,  174 => 54,  168 => 59,  163 => 69,  158 => 58,  155 => 66,  139 => 22,  135 => 59,  132 => 48,  127 => 45,  109 => 60,  103 => 59,  90 => 34,  86 => 10,  65 => 20,  49 => 9,  37 => 6,  43 => 7,  29 => 3,  44 => 10,  25 => 4,  105 => 31,  96 => 38,  93 => 20,  83 => 27,  76 => 22,  72 => 36,  68 => 12,  58 => 16,  50 => 13,  41 => 8,  27 => 4,  24 => 1,  22 => 2,  19 => 1,  94 => 27,  88 => 31,  79 => 30,  59 => 15,  35 => 5,  31 => 4,  26 => 2,  21 => 2,  184 => 66,  178 => 71,  171 => 60,  165 => 79,  162 => 57,  157 => 67,  153 => 73,  151 => 52,  143 => 59,  138 => 59,  136 => 84,  133 => 43,  130 => 47,  122 => 37,  119 => 49,  116 => 46,  111 => 57,  108 => 44,  102 => 41,  98 => 39,  95 => 37,  92 => 28,  89 => 48,  85 => 33,  81 => 5,  73 => 18,  64 => 13,  60 => 23,  57 => 14,  54 => 14,  51 => 15,  48 => 16,  45 => 8,  42 => 7,  39 => 6,  36 => 5,  33 => 4,  30 => 5,);
    }
}
