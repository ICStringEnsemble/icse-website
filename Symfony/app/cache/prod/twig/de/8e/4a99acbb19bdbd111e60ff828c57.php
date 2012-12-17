<?php

/* TwigBundle:Exception:traces.xml.twig */
class __TwigTemplate_de8e4a99acbb19bdbd111e60ff828c57 extends Twig_Template
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
        echo "        <traces>
";
        // line 2
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["exception"]) ? $context["exception"] : null), "trace"));
        foreach ($context['_seq'] as $context["_key"] => $context["trace"]) {
            // line 3
            echo "            <trace>
";
            // line 4
            $this->env->loadTemplate("TwigBundle:Exception:trace.txt.twig")->display(array("trace" => (isset($context["trace"]) ? $context["trace"] : null)));
            // line 5
            echo "
            </trace>
";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['trace'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 8
        echo "        </traces>
";
    }

    public function getTemplateName()
    {
        return "TwigBundle:Exception:traces.xml.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  202 => 3,  200 => 2,  186 => 1,  181 => 54,  176 => 55,  174 => 54,  168 => 52,  163 => 31,  158 => 26,  155 => 25,  139 => 22,  135 => 17,  132 => 16,  127 => 11,  109 => 52,  103 => 49,  90 => 46,  86 => 45,  65 => 35,  49 => 16,  37 => 12,  43 => 8,  29 => 4,  44 => 7,  25 => 6,  105 => 24,  96 => 21,  93 => 20,  83 => 18,  76 => 41,  72 => 14,  68 => 12,  58 => 31,  50 => 8,  41 => 14,  27 => 4,  24 => 3,  22 => 2,  19 => 1,  94 => 39,  88 => 6,  79 => 17,  59 => 22,  35 => 11,  31 => 5,  26 => 3,  21 => 1,  184 => 70,  178 => 66,  171 => 53,  165 => 58,  162 => 57,  157 => 56,  153 => 54,  151 => 53,  143 => 48,  138 => 45,  136 => 44,  133 => 43,  130 => 42,  122 => 37,  119 => 36,  116 => 60,  111 => 57,  108 => 31,  102 => 30,  98 => 47,  95 => 28,  92 => 27,  89 => 19,  85 => 24,  81 => 43,  73 => 19,  64 => 15,  60 => 13,  57 => 12,  54 => 29,  51 => 25,  48 => 14,  45 => 15,  42 => 6,  39 => 8,  36 => 5,  33 => 5,  30 => 3,);
    }
}
