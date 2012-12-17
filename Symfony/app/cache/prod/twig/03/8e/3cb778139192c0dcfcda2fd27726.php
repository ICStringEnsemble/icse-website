<?php

/* TwigBundle:Exception:exception.txt.twig */
class __TwigTemplate_038e3cb778139192c0dcfcda2fd27726 extends Twig_Template
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
        echo "[exception] ";
        echo twig_escape_filter($this->env, (((((isset($context["status_code"]) ? $context["status_code"] : null) . " | ") . (isset($context["status_text"]) ? $context["status_text"] : null)) . " | ") . $this->getAttribute((isset($context["exception"]) ? $context["exception"] : null), "class")), "html", null, true);
        echo "
[message] ";
        // line 2
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["exception"]) ? $context["exception"] : null), "message"), "html", null, true);
        echo "
";
        // line 3
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["exception"]) ? $context["exception"] : null), "toarray"));
        foreach ($context['_seq'] as $context["i"] => $context["e"]) {
            // line 4
            echo "[";
            echo twig_escape_filter($this->env, ((isset($context["i"]) ? $context["i"] : null) + 1), "html", null, true);
            echo "] ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["e"]) ? $context["e"] : null), "class"), "html", null, true);
            echo ": ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["e"]) ? $context["e"] : null), "message"), "html", null, true);
            echo "
";
            // line 5
            $this->env->loadTemplate("TwigBundle:Exception:traces.txt.twig")->display(array("exception" => (isset($context["e"]) ? $context["e"] : null)));
            // line 6
            echo "
";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['i'], $context['e'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
    }

    public function getTemplateName()
    {
        return "TwigBundle:Exception:exception.txt.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  32 => 4,  28 => 3,  46 => 7,  225 => 96,  216 => 90,  212 => 88,  205 => 84,  201 => 83,  196 => 80,  194 => 79,  191 => 78,  189 => 77,  180 => 72,  172 => 67,  159 => 61,  154 => 59,  147 => 55,  121 => 45,  118 => 44,  114 => 43,  104 => 36,  100 => 34,  78 => 28,  75 => 27,  71 => 26,  63 => 24,  34 => 11,  202 => 3,  200 => 2,  186 => 76,  181 => 54,  176 => 55,  174 => 54,  168 => 52,  163 => 63,  158 => 26,  155 => 25,  139 => 22,  135 => 17,  132 => 48,  127 => 46,  109 => 52,  103 => 49,  90 => 46,  86 => 45,  65 => 35,  49 => 16,  37 => 12,  43 => 6,  29 => 4,  44 => 7,  25 => 3,  105 => 24,  96 => 21,  93 => 20,  83 => 18,  76 => 41,  72 => 14,  68 => 12,  58 => 22,  50 => 8,  41 => 5,  27 => 4,  24 => 2,  22 => 2,  19 => 1,  94 => 39,  88 => 6,  79 => 17,  59 => 22,  35 => 7,  31 => 5,  26 => 3,  21 => 2,  184 => 70,  178 => 71,  171 => 53,  165 => 58,  162 => 57,  157 => 60,  153 => 54,  151 => 53,  143 => 54,  138 => 51,  136 => 50,  133 => 43,  130 => 47,  122 => 37,  119 => 36,  116 => 60,  111 => 57,  108 => 31,  102 => 30,  98 => 47,  95 => 31,  92 => 27,  89 => 19,  85 => 24,  81 => 43,  73 => 19,  64 => 15,  60 => 23,  57 => 12,  54 => 29,  51 => 25,  48 => 19,  45 => 15,  42 => 6,  39 => 8,  36 => 5,  33 => 6,  30 => 3,);
    }
}
