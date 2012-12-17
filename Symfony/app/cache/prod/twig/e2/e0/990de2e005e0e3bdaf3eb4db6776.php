<?php

/* TwigBundle:Exception:trace.txt.twig */
class __TwigTemplate_e2e0990de2e005e0e3bdaf3eb4db6776 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        if ($this->getAttribute((isset($context["trace"]) ? $context["trace"] : null), "function")) {
            // line 2
            echo "                at ";
            echo twig_escape_filter($this->env, (($this->getAttribute((isset($context["trace"]) ? $context["trace"] : null), "class") . $this->getAttribute((isset($context["trace"]) ? $context["trace"] : null), "type")) . $this->getAttribute((isset($context["trace"]) ? $context["trace"] : null), "function")), "html", null, true);
            echo "(";
            echo twig_escape_filter($this->env, $this->env->getExtension('code')->formatArgsAsText($this->getAttribute((isset($context["trace"]) ? $context["trace"] : null), "args")), "html", null, true);
            echo ")
";
        } else {
            // line 4
            echo "                at n/a
";
        }
        // line 6
        if (($this->getAttribute((isset($context["trace"]) ? $context["trace"] : null), "file", array(), "any", true, true) && $this->getAttribute((isset($context["trace"]) ? $context["trace"] : null), "line", array(), "any", true, true))) {
            // line 7
            echo "                    in ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["trace"]) ? $context["trace"] : null), "file"), "html", null, true);
            echo " line ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["trace"]) ? $context["trace"] : null), "line"), "html", null, true);
            echo "
";
        }
    }

    public function getTemplateName()
    {
        return "TwigBundle:Exception:trace.txt.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  46 => 7,  225 => 96,  216 => 90,  212 => 88,  205 => 84,  201 => 83,  196 => 80,  194 => 79,  191 => 78,  189 => 77,  180 => 72,  172 => 67,  159 => 61,  154 => 59,  147 => 55,  121 => 45,  118 => 44,  114 => 43,  104 => 36,  100 => 34,  78 => 28,  75 => 27,  71 => 26,  63 => 24,  34 => 11,  202 => 3,  200 => 2,  186 => 76,  181 => 54,  176 => 55,  174 => 54,  168 => 52,  163 => 63,  158 => 26,  155 => 25,  139 => 22,  135 => 17,  132 => 48,  127 => 46,  109 => 52,  103 => 49,  90 => 46,  86 => 45,  65 => 35,  49 => 16,  37 => 12,  43 => 8,  29 => 4,  44 => 7,  25 => 3,  105 => 24,  96 => 21,  93 => 20,  83 => 18,  76 => 41,  72 => 14,  68 => 12,  58 => 22,  50 => 8,  41 => 15,  27 => 4,  24 => 4,  22 => 2,  19 => 1,  94 => 39,  88 => 6,  79 => 17,  59 => 22,  35 => 7,  31 => 5,  26 => 3,  21 => 2,  184 => 70,  178 => 71,  171 => 53,  165 => 58,  162 => 57,  157 => 60,  153 => 54,  151 => 53,  143 => 54,  138 => 51,  136 => 50,  133 => 43,  130 => 47,  122 => 37,  119 => 36,  116 => 60,  111 => 57,  108 => 31,  102 => 30,  98 => 47,  95 => 31,  92 => 27,  89 => 19,  85 => 24,  81 => 43,  73 => 19,  64 => 15,  60 => 23,  57 => 12,  54 => 29,  51 => 25,  48 => 19,  45 => 15,  42 => 6,  39 => 8,  36 => 5,  33 => 6,  30 => 3,);
    }
}
