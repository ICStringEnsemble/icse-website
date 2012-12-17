<?php

/* IcseMembersBundle:Default:index.html.twig */
class __TwigTemplate_fad5df57c1544082f0d0f1bc0d949962 extends Twig_Template
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
        echo " - Members";
    }

    // line 5
    public function block_content($context, array $blocks = array())
    {
        // line 6
        echo "
<h1>Welcome to the Members Area!</h1>

<p>
Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod
tempor invidunt ut l

Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod
tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At
vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren,
no sea takimata sanctus est Lorem ipsum dolor sit amet.
Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod
tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At
vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren,
no sea takimata sanctus est Lorem ipsum dolor sit amet.
Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod
tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At
vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren,
no sea takimata sanctus est Lorem ipsum dolor sit amet.
</p>


";
    }

    public function getTemplateName()
    {
        return "IcseMembersBundle:Default:index.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  167 => 81,  148 => 72,  145 => 70,  131 => 59,  113 => 45,  80 => 26,  110 => 43,  99 => 37,  56 => 22,  101 => 40,  278 => 111,  269 => 108,  266 => 107,  264 => 106,  253 => 97,  244 => 95,  240 => 94,  217 => 73,  208 => 71,  195 => 67,  192 => 66,  140 => 49,  134 => 46,  124 => 51,  241 => 2,  228 => 1,  197 => 72,  190 => 79,  179 => 64,  175 => 61,  169 => 62,  107 => 29,  53 => 12,  52 => 14,  40 => 6,  301 => 100,  295 => 96,  292 => 95,  289 => 94,  287 => 93,  282 => 118,  276 => 86,  273 => 109,  270 => 84,  268 => 83,  263 => 80,  249 => 79,  245 => 77,  230 => 75,  222 => 77,  220 => 72,  215 => 70,  211 => 72,  204 => 66,  198 => 63,  185 => 61,  183 => 60,  177 => 58,  160 => 60,  149 => 52,  123 => 47,  120 => 46,  82 => 21,  38 => 6,  150 => 71,  144 => 50,  142 => 50,  129 => 45,  125 => 44,  117 => 30,  112 => 31,  106 => 35,  87 => 28,  70 => 17,  67 => 33,  61 => 17,  47 => 9,  91 => 36,  84 => 19,  74 => 19,  66 => 20,  55 => 13,  32 => 6,  28 => 2,  46 => 13,  225 => 96,  216 => 90,  212 => 88,  205 => 74,  201 => 69,  196 => 80,  194 => 71,  191 => 78,  189 => 77,  180 => 72,  172 => 67,  159 => 61,  154 => 54,  147 => 53,  121 => 42,  118 => 36,  114 => 47,  104 => 40,  100 => 30,  78 => 20,  75 => 29,  71 => 18,  63 => 18,  34 => 11,  202 => 3,  200 => 2,  186 => 63,  181 => 54,  176 => 55,  174 => 54,  168 => 59,  163 => 57,  158 => 58,  155 => 25,  139 => 22,  135 => 59,  132 => 48,  127 => 45,  109 => 39,  103 => 34,  90 => 26,  86 => 10,  65 => 28,  49 => 9,  37 => 6,  43 => 7,  29 => 3,  44 => 12,  25 => 4,  105 => 31,  96 => 36,  93 => 20,  83 => 27,  76 => 22,  72 => 36,  68 => 12,  58 => 16,  50 => 13,  41 => 8,  27 => 4,  24 => 1,  22 => 2,  19 => 1,  94 => 27,  88 => 31,  79 => 30,  59 => 15,  35 => 5,  31 => 4,  26 => 2,  21 => 2,  184 => 66,  178 => 71,  171 => 60,  165 => 79,  162 => 57,  157 => 74,  153 => 73,  151 => 52,  143 => 59,  138 => 47,  136 => 50,  133 => 43,  130 => 47,  122 => 37,  119 => 49,  116 => 46,  111 => 57,  108 => 44,  102 => 30,  98 => 28,  95 => 37,  92 => 28,  89 => 11,  85 => 33,  81 => 5,  73 => 18,  64 => 13,  60 => 23,  57 => 14,  54 => 14,  51 => 9,  48 => 16,  45 => 8,  42 => 7,  39 => 6,  36 => 5,  33 => 4,  30 => 5,);
    }
}
