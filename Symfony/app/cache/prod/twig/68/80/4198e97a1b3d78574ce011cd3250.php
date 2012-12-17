<?php

/* IcsePublicBundle:Security:login.html.twig */
class __TwigTemplate_68804198e97a1b3d78574ce011cd3250 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("IcsePublicBundle:Default:template.html.twig");

        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "IcsePublicBundle:Default:template.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 2
        $context["currentPage"] = "members";
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 4
    public function block_title($context, array $blocks = array())
    {
        echo " - Login";
    }

    // line 6
    public function block_content($context, array $blocks = array())
    {
        // line 7
        echo "
<h1>Login</h1>

<form action=\"";
        // line 10
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("IcsePublicBundle_login_check"), "html", null, true);
        echo "\" method=\"post\">
  <ul class=\"global_errors\"><li>
  ";
        // line 12
        if ((isset($context["error"]) ? $context["error"] : null)) {
            // line 13
            echo "  ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["error"]) ? $context["error"] : null), "message"), "html", null, true);
            echo "
  ";
        }
        // line 15
        echo "  </li></ul>
  <div>
    <label for=\"username\">Username / E-mail:</label>
    <input type=\"text\" id=\"username\" name=\"_username\" value=\"";
        // line 18
        echo twig_escape_filter($this->env, (isset($context["last_username"]) ? $context["last_username"] : null), "html", null, true);
        echo "\" />
  </div>
  <div>
    <label for=\"password\">Password:</label>
    <input type=\"password\" id=\"password\" name=\"_password\" />
  </div>
    ";
        // line 28
        echo "
    <input type=\"submit\" id=\"login_button\" value=\"Login\"/>
  <input type=\"checkbox\" id=\"remember_me\" name=\"_remember_me\" />
  <label for=\"remember_me\" id=\"remember_me_label\" >Stay logged in</label>
</form>

<div id=\"end_form\"></div>

";
    }

    public function getTemplateName()
    {
        return "IcsePublicBundle:Security:login.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  52 => 13,  40 => 7,  301 => 100,  295 => 96,  292 => 95,  289 => 94,  287 => 93,  282 => 90,  276 => 86,  273 => 85,  270 => 84,  268 => 83,  263 => 80,  249 => 79,  245 => 77,  230 => 75,  222 => 73,  220 => 72,  215 => 70,  211 => 69,  204 => 66,  198 => 63,  185 => 61,  183 => 60,  177 => 58,  160 => 57,  149 => 51,  123 => 47,  120 => 46,  82 => 24,  38 => 6,  150 => 55,  144 => 50,  142 => 50,  129 => 45,  125 => 44,  117 => 42,  112 => 42,  106 => 35,  87 => 28,  70 => 20,  67 => 19,  61 => 16,  47 => 9,  91 => 20,  84 => 19,  74 => 16,  66 => 15,  55 => 15,  32 => 4,  28 => 3,  46 => 7,  225 => 96,  216 => 90,  212 => 88,  205 => 84,  201 => 83,  196 => 80,  194 => 62,  191 => 78,  189 => 77,  180 => 72,  172 => 67,  159 => 61,  154 => 59,  147 => 53,  121 => 45,  118 => 44,  114 => 43,  104 => 36,  100 => 33,  78 => 24,  75 => 23,  71 => 19,  63 => 18,  34 => 11,  202 => 3,  200 => 2,  186 => 76,  181 => 54,  176 => 55,  174 => 54,  168 => 52,  163 => 63,  158 => 58,  155 => 25,  139 => 22,  135 => 17,  132 => 48,  127 => 49,  109 => 41,  103 => 34,  90 => 46,  86 => 45,  65 => 16,  49 => 16,  37 => 6,  43 => 7,  29 => 3,  44 => 12,  25 => 4,  105 => 24,  96 => 21,  93 => 20,  83 => 18,  76 => 22,  72 => 28,  68 => 12,  58 => 15,  50 => 12,  41 => 9,  27 => 4,  24 => 3,  22 => 2,  19 => 1,  94 => 39,  88 => 27,  79 => 23,  59 => 22,  35 => 5,  31 => 4,  26 => 2,  21 => 2,  184 => 70,  178 => 71,  171 => 53,  165 => 58,  162 => 57,  157 => 56,  153 => 54,  151 => 52,  143 => 54,  138 => 47,  136 => 50,  133 => 43,  130 => 47,  122 => 37,  119 => 36,  116 => 60,  111 => 57,  108 => 31,  102 => 30,  98 => 34,  95 => 33,  92 => 28,  89 => 19,  85 => 24,  81 => 25,  73 => 19,  64 => 17,  60 => 23,  57 => 14,  54 => 10,  51 => 9,  48 => 13,  45 => 10,  42 => 7,  39 => 9,  36 => 5,  33 => 4,  30 => 7,);
    }
}
