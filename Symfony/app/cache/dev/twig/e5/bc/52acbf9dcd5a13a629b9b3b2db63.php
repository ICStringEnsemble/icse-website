<?php

/* IcsePublicBundle:Default:index.html.twig */
class __TwigTemplate_e5bc52acbf9dcd5a13a629b9b3b2db63 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->blocks = array(
            'page_title' => array($this, 'block_page_title'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<html>
<head>
<title>
  Imperial College String Ensemble";
        // line 4
        $this->displayBlock('page_title', $context, $blocks);
        // line 5
        echo "</title>
<link rel=\"icon\" href=\"{ asset('favicon.ico') }}\" type=\"image/x-icon\">
<link rel=\"shortcut icon\" href=\"";
        // line 7
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("favicon.ico"), "html", null, true);
        echo "\" type=\"image/x-icon\"> 
</head>
<body>
  Hello !
</body>
</html>
";
    }

    // line 4
    public function block_page_title($context, array $blocks = array())
    {
    }

    public function getTemplateName()
    {
        return "IcsePublicBundle:Default:index.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }
}
