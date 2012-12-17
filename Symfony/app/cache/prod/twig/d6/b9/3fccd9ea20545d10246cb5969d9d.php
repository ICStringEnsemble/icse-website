<?php

/* IcsePublicBundle:SignUp:freshers_template.html.twig */
class __TwigTemplate_d6b93fccd9ea20545d10246cb5969d9d extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'stylesheets' => array($this, 'block_stylesheets'),
            'javascripts' => array($this, 'block_javascripts'),
            'header_to_footer' => array($this, 'block_header_to_footer'),
            'content' => array($this, 'block_content'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE HTML>
<html>
<head>
<title>
  Imperial College String Ensemble";
        // line 5
        $this->displayBlock('title', $context, $blocks);
        // line 6
        echo "</title>
<meta charset=\"UTF-8\"> 
<link rel=\"icon\" href=\"";
        // line 8
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("favicon.ico"), "html", null, true);
        echo "\" type=\"image/x-icon\">
<link rel=\"shortcut icon\" href=\"";
        // line 9
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("favicon.ico"), "html", null, true);
        echo "\" type=\"image/x-icon\"> 
";
        // line 10
        $this->displayBlock('stylesheets', $context, $blocks);
        // line 13
        $this->displayBlock('javascripts', $context, $blocks);
        // line 16
        echo "</head>
<body>
  <div id=\"above_footer\">
    <header>
    <hgroup>
    <h1>
      <a target=\"_top\" href=\"http://union.ic.ac.uk/arts/stringensemble\"><img src=\"";
        // line 22
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/icsepublic/images/banner_logo.png"), "html", null, true);
        echo "\" alt=\"Imperial College String Ensemble\"/></a>
    </h1>
    </hgroup>
    </header>
    <nav>
    </nav>
    ";
        // line 28
        $this->displayBlock('header_to_footer', $context, $blocks);
        // line 33
        echo "    <div class=\"footer_push\"></div>
  </div>
  <footer>
  Copyright &copy; 2005&ndash;";
        // line 36
        echo twig_escape_filter($this->env, twig_date_format_filter($this->env, "now", "Y"), "html", null, true);
        echo " Imperial College String Ensemble
  </footer>
</body>
</html>
";
    }

    // line 5
    public function block_title($context, array $blocks = array())
    {
    }

    // line 10
    public function block_stylesheets($context, array $blocks = array())
    {
        // line 11
        echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"screen\" href=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/icsepublic/css/main.css"), "html", null, true);
        echo "\" />
";
    }

    // line 13
    public function block_javascripts($context, array $blocks = array())
    {
        // line 14
        echo "<script src=\"//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js\"></script> 
";
    }

    // line 28
    public function block_header_to_footer($context, array $blocks = array())
    {
        // line 29
        echo "    <section>
    ";
        // line 30
        $this->displayBlock('content', $context, $blocks);
        // line 31
        echo "    </section>
    ";
    }

    // line 30
    public function block_content($context, array $blocks = array())
    {
    }

    public function getTemplateName()
    {
        return "IcsePublicBundle:SignUp:freshers_template.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  110 => 30,  99 => 14,  56 => 22,  101 => 40,  278 => 111,  269 => 108,  266 => 107,  264 => 106,  253 => 97,  244 => 95,  240 => 94,  217 => 73,  208 => 71,  195 => 67,  192 => 66,  140 => 49,  134 => 46,  124 => 51,  241 => 2,  228 => 1,  197 => 72,  190 => 65,  179 => 64,  175 => 61,  169 => 62,  107 => 29,  53 => 12,  52 => 14,  40 => 9,  301 => 100,  295 => 96,  292 => 95,  289 => 94,  287 => 93,  282 => 118,  276 => 86,  273 => 109,  270 => 84,  268 => 83,  263 => 80,  249 => 79,  245 => 77,  230 => 75,  222 => 77,  220 => 72,  215 => 70,  211 => 72,  204 => 66,  198 => 63,  185 => 61,  183 => 60,  177 => 58,  160 => 60,  149 => 52,  123 => 47,  120 => 46,  82 => 21,  38 => 7,  150 => 71,  144 => 50,  142 => 50,  129 => 45,  125 => 44,  117 => 30,  112 => 31,  106 => 35,  87 => 28,  70 => 27,  67 => 33,  61 => 17,  47 => 9,  91 => 36,  84 => 19,  74 => 19,  66 => 20,  55 => 13,  32 => 6,  28 => 2,  46 => 13,  225 => 96,  216 => 90,  212 => 88,  205 => 74,  201 => 69,  196 => 80,  194 => 71,  191 => 78,  189 => 77,  180 => 72,  172 => 67,  159 => 61,  154 => 54,  147 => 53,  121 => 42,  118 => 36,  114 => 47,  104 => 28,  100 => 30,  78 => 20,  75 => 29,  71 => 18,  63 => 18,  34 => 11,  202 => 3,  200 => 2,  186 => 63,  181 => 54,  176 => 55,  174 => 54,  168 => 59,  163 => 57,  158 => 58,  155 => 25,  139 => 22,  135 => 59,  132 => 48,  127 => 45,  109 => 39,  103 => 34,  90 => 26,  86 => 10,  65 => 28,  49 => 10,  37 => 6,  43 => 7,  29 => 4,  44 => 10,  25 => 4,  105 => 31,  96 => 13,  93 => 20,  83 => 18,  76 => 22,  72 => 36,  68 => 12,  58 => 16,  50 => 13,  41 => 8,  27 => 4,  24 => 1,  22 => 2,  19 => 1,  94 => 27,  88 => 24,  79 => 30,  59 => 15,  35 => 6,  31 => 4,  26 => 2,  21 => 2,  184 => 66,  178 => 71,  171 => 60,  165 => 58,  162 => 57,  157 => 55,  153 => 54,  151 => 52,  143 => 59,  138 => 47,  136 => 50,  133 => 43,  130 => 47,  122 => 37,  119 => 49,  116 => 60,  111 => 57,  108 => 44,  102 => 30,  98 => 28,  95 => 37,  92 => 28,  89 => 11,  85 => 33,  81 => 5,  73 => 19,  64 => 15,  60 => 23,  57 => 14,  54 => 14,  51 => 9,  48 => 16,  45 => 9,  42 => 7,  39 => 6,  36 => 8,  33 => 4,  30 => 5,);
    }
}
