<?php

/* ::base.html.twig */
class __TwigTemplate_0c222f40f81d6994443ecd3b3ead9da6 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'stylesheets' => array($this, 'block_stylesheets'),
            'body' => array($this, 'block_body'),
            'javascripts' => array($this, 'block_javascripts'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<html>
    <head>
        <meta charset=\"UTF-8\" />
        <title>";
        // line 5
        $this->displayBlock('title', $context, $blocks);
        echo "</title>
        ";
        // line 6
        $this->displayBlock('stylesheets', $context, $blocks);
        // line 7
        echo "        <link rel=\"shortcut icon\" href=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("favicon.ico"), "html", null, true);
        echo "\" />
    </head>
    <body>
        ";
        // line 10
        $this->displayBlock('body', $context, $blocks);
        // line 11
        echo "        ";
        $this->displayBlock('javascripts', $context, $blocks);
        // line 12
        echo "    </body>
</html>
";
    }

    // line 5
    public function block_title($context, array $blocks = array())
    {
        echo "Welcome!";
    }

    // line 6
    public function block_stylesheets($context, array $blocks = array())
    {
    }

    // line 10
    public function block_body($context, array $blocks = array())
    {
    }

    // line 11
    public function block_javascripts($context, array $blocks = array())
    {
    }

    public function getTemplateName()
    {
        return "::base.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  69 => 11,  23 => 1,  128 => 55,  126 => 54,  167 => 81,  148 => 72,  145 => 61,  131 => 59,  113 => 45,  80 => 26,  110 => 43,  99 => 37,  56 => 22,  101 => 40,  278 => 111,  269 => 108,  266 => 107,  264 => 106,  253 => 97,  244 => 95,  240 => 94,  217 => 73,  208 => 71,  195 => 67,  192 => 66,  140 => 49,  134 => 57,  124 => 51,  241 => 2,  228 => 1,  197 => 72,  190 => 79,  179 => 64,  175 => 61,  169 => 62,  107 => 29,  53 => 5,  52 => 13,  40 => 6,  301 => 100,  295 => 96,  292 => 95,  289 => 94,  287 => 93,  282 => 118,  276 => 86,  273 => 109,  270 => 84,  268 => 83,  263 => 80,  249 => 79,  245 => 77,  230 => 75,  222 => 77,  220 => 72,  215 => 70,  211 => 72,  204 => 66,  198 => 63,  185 => 61,  183 => 60,  177 => 58,  160 => 60,  149 => 52,  123 => 47,  120 => 50,  82 => 31,  38 => 6,  150 => 71,  144 => 50,  142 => 50,  129 => 45,  125 => 44,  117 => 30,  112 => 47,  106 => 43,  87 => 28,  70 => 17,  67 => 33,  61 => 17,  47 => 12,  91 => 36,  84 => 32,  74 => 36,  66 => 20,  55 => 14,  32 => 6,  28 => 2,  46 => 11,  225 => 96,  216 => 90,  212 => 88,  205 => 74,  201 => 69,  196 => 80,  194 => 71,  191 => 78,  189 => 77,  180 => 72,  172 => 67,  159 => 61,  154 => 54,  147 => 53,  121 => 42,  118 => 36,  114 => 48,  104 => 40,  100 => 30,  78 => 20,  75 => 29,  71 => 22,  63 => 19,  34 => 11,  202 => 3,  200 => 2,  186 => 63,  181 => 54,  176 => 55,  174 => 54,  168 => 59,  163 => 69,  158 => 58,  155 => 66,  139 => 22,  135 => 59,  132 => 48,  127 => 45,  109 => 60,  103 => 59,  90 => 34,  86 => 10,  65 => 20,  49 => 9,  37 => 6,  43 => 7,  29 => 5,  44 => 11,  25 => 4,  105 => 31,  96 => 38,  93 => 20,  83 => 27,  76 => 22,  72 => 36,  68 => 12,  58 => 16,  50 => 13,  41 => 8,  27 => 4,  24 => 1,  22 => 2,  19 => 1,  94 => 27,  88 => 31,  79 => 30,  59 => 6,  35 => 7,  31 => 4,  26 => 2,  21 => 2,  184 => 66,  178 => 71,  171 => 60,  165 => 79,  162 => 57,  157 => 67,  153 => 73,  151 => 52,  143 => 59,  138 => 59,  136 => 84,  133 => 43,  130 => 47,  122 => 37,  119 => 49,  116 => 46,  111 => 57,  108 => 44,  102 => 41,  98 => 39,  95 => 37,  92 => 28,  89 => 48,  85 => 33,  81 => 5,  73 => 18,  64 => 10,  60 => 23,  57 => 14,  54 => 14,  51 => 15,  48 => 16,  45 => 8,  42 => 10,  39 => 6,  36 => 5,  33 => 6,  30 => 5,);
    }
}
