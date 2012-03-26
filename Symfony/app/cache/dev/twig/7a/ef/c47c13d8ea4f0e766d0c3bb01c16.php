<?php

/* DavidHelloWorldBundle:Default:index.html.twig */
class __TwigTemplate_7aefc47c13d8ea4f0e766d0c3bb01c16 extends Twig_Template
{
    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "Hello ";
        echo twig_escape_filter($this->env, $this->getContext($context, "name"), "html", null, true);
        echo "!
";
    }

    public function getTemplateName()
    {
        return "DavidHelloWorldBundle:Default:index.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }
}
