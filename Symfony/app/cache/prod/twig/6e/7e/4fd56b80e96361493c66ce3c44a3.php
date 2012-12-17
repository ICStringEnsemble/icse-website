<?php

/* TwigBundle:Exception:error.html.twig */
class __TwigTemplate_6e7e4fd56b80e96361493c66ce3c44a3 extends Twig_Template
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
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_title($context, array $blocks = array())
    {
        echo " - Error ";
        echo twig_escape_filter($this->env, (isset($context["status_code"]) ? $context["status_code"] : null), "html", null, true);
    }

    // line 5
    public function block_content($context, array $blocks = array())
    {
        // line 6
        echo "
<h1>Whoops! An Error Occurred</h1>
<h2>The Server returned \"Error ";
        // line 8
        echo twig_escape_filter($this->env, (isset($context["status_code"]) ? $context["status_code"] : null), "html", null, true);
        echo ": ";
        echo twig_escape_filter($this->env, (isset($context["status_text"]) ? $context["status_text"] : null), "html", null, true);
        echo "\"</h2>

<p>Something is broken. Please e-mail us at icse@imperial.ac.uk and
let us know what you were doing when this error occurred. We will
fix it as soon as possible. Sorry for any inconvenience caused.</p>

";
    }

    public function getTemplateName()
    {
        return "TwigBundle:Exception:error.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  43 => 8,  29 => 3,  44 => 7,  25 => 3,  105 => 24,  96 => 21,  93 => 20,  83 => 18,  76 => 16,  72 => 14,  68 => 12,  58 => 9,  50 => 8,  41 => 7,  27 => 4,  24 => 3,  22 => 2,  19 => 1,  94 => 39,  88 => 6,  79 => 17,  59 => 22,  35 => 5,  31 => 4,  26 => 4,  21 => 1,  184 => 70,  178 => 66,  171 => 62,  165 => 58,  162 => 57,  157 => 56,  153 => 54,  151 => 53,  143 => 48,  138 => 45,  136 => 44,  133 => 43,  130 => 42,  122 => 37,  119 => 36,  116 => 35,  111 => 32,  108 => 31,  102 => 30,  98 => 22,  95 => 28,  92 => 27,  89 => 19,  85 => 24,  81 => 40,  73 => 19,  64 => 15,  60 => 13,  57 => 12,  54 => 11,  51 => 9,  48 => 14,  45 => 8,  42 => 6,  39 => 6,  36 => 5,  33 => 5,  30 => 3,);
    }
}
