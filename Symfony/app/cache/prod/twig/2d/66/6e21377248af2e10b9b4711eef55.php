<?php

/* IcsePublicBundle:Default:generic_page.html.twig */
class __TwigTemplate_2d666e21377248af2e10b9b4711eef55 extends Twig_Template
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
        $context["currentPage"] = (isset($context["pageId"]) ? $context["pageId"] : null);
        $this->getParent($context)->display($context, array_merge($this->blocks, $blocks));
    }

    // line 4
    public function block_title($context, array $blocks = array())
    {
        echo " - ";
        echo twig_escape_filter($this->env, (isset($context["pageTitle"]) ? $context["pageTitle"] : null), "html", null, true);
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
        echo (isset($context["pageBody"]) ? $context["pageBody"] : null);
        echo "
";
        // line 10
        echo " 

";
        // line 12
        if (array_key_exists("reloadPeriod", $context)) {
            // line 13
            echo "<script>
\$(document).ready(function(){ 
  setTimeout(function(){window.top.location.reload(true);}, ";
            // line 15
            echo twig_escape_filter($this->env, (isset($context["reloadPeriod"]) ? $context["reloadPeriod"] : null), "html", null, true);
            echo ");
});
</script>
";
        }
        // line 19
        echo "
";
    }

    public function getTemplateName()
    {
        return "IcsePublicBundle:Default:generic_page.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  53 => 12,  52 => 13,  40 => 7,  301 => 100,  295 => 96,  292 => 95,  289 => 94,  287 => 93,  282 => 90,  276 => 86,  273 => 85,  270 => 84,  268 => 83,  263 => 80,  249 => 79,  245 => 77,  230 => 75,  222 => 73,  220 => 72,  215 => 70,  211 => 69,  204 => 66,  198 => 63,  185 => 61,  183 => 60,  177 => 58,  160 => 57,  149 => 51,  123 => 47,  120 => 46,  82 => 24,  38 => 6,  150 => 55,  144 => 50,  142 => 50,  129 => 45,  125 => 44,  117 => 42,  112 => 42,  106 => 35,  87 => 28,  70 => 20,  67 => 19,  61 => 16,  47 => 9,  91 => 20,  84 => 19,  74 => 16,  66 => 19,  55 => 13,  32 => 4,  28 => 3,  46 => 7,  225 => 96,  216 => 90,  212 => 88,  205 => 84,  201 => 83,  196 => 80,  194 => 62,  191 => 78,  189 => 77,  180 => 72,  172 => 67,  159 => 61,  154 => 59,  147 => 53,  121 => 45,  118 => 44,  114 => 43,  104 => 36,  100 => 33,  78 => 24,  75 => 23,  71 => 19,  63 => 18,  34 => 11,  202 => 3,  200 => 2,  186 => 76,  181 => 54,  176 => 55,  174 => 54,  168 => 52,  163 => 63,  158 => 58,  155 => 25,  139 => 22,  135 => 17,  132 => 48,  127 => 49,  109 => 41,  103 => 34,  90 => 46,  86 => 45,  65 => 16,  49 => 10,  37 => 6,  43 => 7,  29 => 4,  44 => 12,  25 => 4,  105 => 24,  96 => 21,  93 => 20,  83 => 18,  76 => 22,  72 => 28,  68 => 12,  58 => 15,  50 => 12,  41 => 9,  27 => 4,  24 => 2,  22 => 2,  19 => 1,  94 => 39,  88 => 27,  79 => 23,  59 => 15,  35 => 5,  31 => 4,  26 => 2,  21 => 2,  184 => 70,  178 => 71,  171 => 53,  165 => 58,  162 => 57,  157 => 56,  153 => 54,  151 => 52,  143 => 54,  138 => 47,  136 => 50,  133 => 43,  130 => 47,  122 => 37,  119 => 36,  116 => 60,  111 => 57,  108 => 31,  102 => 30,  98 => 34,  95 => 33,  92 => 28,  89 => 19,  85 => 24,  81 => 25,  73 => 19,  64 => 17,  60 => 23,  57 => 14,  54 => 10,  51 => 9,  48 => 13,  45 => 9,  42 => 8,  39 => 7,  36 => 6,  33 => 4,  30 => 7,);
    }
}
