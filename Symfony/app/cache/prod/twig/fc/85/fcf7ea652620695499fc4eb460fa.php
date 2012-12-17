<?php

/* IcsePublicBundle:Default:template.html.twig */
class __TwigTemplate_fc85fcf7ea652620695499fc4eb460fa extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'stylesheets' => array($this, 'block_stylesheets'),
            'javascripts' => array($this, 'block_javascripts'),
            'aboveFooterAttrs' => array($this, 'block_aboveFooterAttrs'),
            'header_to_footer' => array($this, 'block_header_to_footer'),
            'content' => array($this, 'block_content'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 6
        echo "<!DOCTYPE HTML>
<html>
";
        // line 8
        ob_start();
        // line 9
        echo "<head>
<title>
  Imperial College String Ensemble";
        // line 11
        $this->displayBlock('title', $context, $blocks);
        // line 12
        echo "</title>
<meta charset=\"UTF-8\"> 
<link rel=\"icon\" href=\"";
        // line 14
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("favicon.ico"), "html", null, true);
        echo "\" type=\"image/x-icon\">
<link rel=\"shortcut icon\" href=\"";
        // line 15
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("favicon.ico"), "html", null, true);
        echo "\" type=\"image/x-icon\"> 
";
        // line 16
        $this->displayBlock('stylesheets', $context, $blocks);
        // line 25
        $this->displayBlock('javascripts', $context, $blocks);
        echo trim(preg_replace('/>\s+</', '><', ob_get_clean()));
        // line 29
        echo "</head>
<body>
  <div id=\"above_footer\" ";
        // line 31
        $this->displayBlock('aboveFooterAttrs', $context, $blocks);
        echo ">
    <header>
    <hgroup>
    <h1>
      <a href=\"";
        // line 35
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("IcsePublicBundle_home"), "html", null, true);
        echo "\"><img src=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/icsepublic/images/banner_logo.png"), "html", null, true);
        echo "\" alt=\"Imperial College String Ensemble\"/></a>
    </h1>
    </hgroup>
    </header>
    <nav>
    <ul>
      ";
        // line 41
        echo $this->getAttribute($this, "navbar_item", array(0 => (isset($context["currentPage"]) ? $context["currentPage"] : null), 1 => "home", 2 => "Home", 3 => "IcsePublicBundle_home"), "method");
        echo "
      <li><a href=\"#\">News</a></li>
      ";
        // line 43
        echo $this->getAttribute($this, "navbar_item", array(0 => (isset($context["currentPage"]) ? $context["currentPage"] : null), 1 => "about", 2 => "About Us", 3 => "IcsePublicBundle_about"), "method");
        echo "
      <li><a href=\"#\">Events</a></li>
      ";
        // line 45
        echo $this->getAttribute($this, "navbar_item", array(0 => (isset($context["currentPage"]) ? $context["currentPage"] : null), 1 => "join", 2 => "Join Us", 3 => "IcsePublicBundle_join"), "method");
        echo "
      <div ";
        // line 46
        if ((!array_key_exists("status_code", $context))) {
            if ($this->env->getExtension('security')->isGranted("ROLE_USER")) {
                echo "class=\"logged_in\"";
            }
        }
        echo " >
      ";
        // line 47
        echo $this->getAttribute($this, "navbar_item", array(0 => (isset($context["currentPage"]) ? $context["currentPage"] : null), 1 => "members", 2 => "Members", 3 => "IcseMembersBundle_home"), "method");
        echo "
      </div>
      ";
        // line 49
        echo $this->getAttribute($this, "navbar_item", array(0 => (isset($context["currentPage"]) ? $context["currentPage"] : null), 1 => "support", 2 => "Support Us", 3 => "IcsePublicBundle_support"), "method");
        echo "
    </ul>
    </nav>
    ";
        // line 52
        $this->displayBlock('header_to_footer', $context, $blocks);
        // line 57
        echo "    <div class=\"footer_push\"></div>
  </div>
  <footer>
  Copyright &copy; 2005&ndash;";
        // line 60
        echo twig_escape_filter($this->env, twig_date_format_filter($this->env, "now", "Y"), "html", null, true);
        echo " Imperial College String Ensemble | <a href=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("IcsePublicBundle_contact"), "html", null, true);
        echo "\">Contact Us</a>
  </footer>
</body>
</html>
";
    }

    // line 11
    public function block_title($context, array $blocks = array())
    {
    }

    // line 16
    public function block_stylesheets($context, array $blocks = array())
    {
        // line 17
        if (isset($context['assetic']['debug']) && $context['assetic']['debug']) {
            // asset "a5ccc9f_0"
            $context["asset_url"] = isset($context['assetic']['use_controller']) && $context['assetic']['use_controller'] ? $this->env->getExtension('routing')->getPath("_assetic_a5ccc9f_0") : $this->env->getExtension('assets')->getAssetUrl("bundles/icsepublic/css/main_main_1.css");
            // line 22
            echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"screen\" href=\"";
            echo twig_escape_filter($this->env, (isset($context["asset_url"]) ? $context["asset_url"] : null), "html", null, true);
            echo "\" />
";
        } else {
            // asset "a5ccc9f"
            $context["asset_url"] = isset($context['assetic']['use_controller']) && $context['assetic']['use_controller'] ? $this->env->getExtension('routing')->getPath("_assetic_a5ccc9f") : $this->env->getExtension('assets')->getAssetUrl("bundles/icsepublic/css/main.css");
            echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"screen\" href=\"";
            echo twig_escape_filter($this->env, (isset($context["asset_url"]) ? $context["asset_url"] : null), "html", null, true);
            echo "\" />
";
        }
        unset($context["asset_url"]);
    }

    // line 25
    public function block_javascripts($context, array $blocks = array())
    {
        // line 26
        echo "<script src=\"//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js\"></script> 
";
    }

    // line 31
    public function block_aboveFooterAttrs($context, array $blocks = array())
    {
    }

    // line 52
    public function block_header_to_footer($context, array $blocks = array())
    {
        // line 53
        echo "    <section>
    ";
        // line 54
        $this->displayBlock('content', $context, $blocks);
        // line 55
        echo "    </section>
    ";
    }

    // line 54
    public function block_content($context, array $blocks = array())
    {
    }

    // line 1
    public function getnavbar_item($_currentId = null, $_id = null, $_label = null, $_route = null)
    {
        $context = $this->env->mergeGlobals(array(
            "currentId" => $_currentId,
            "id" => $_id,
            "label" => $_label,
            "route" => $_route,
        ));

        $blocks = array();

        ob_start();
        try {
            // line 2
            ob_start();
            // line 3
            echo "<li ";
            if (((isset($context["id"]) ? $context["id"] : null) == (isset($context["currentId"]) ? $context["currentId"] : null))) {
                echo "class='current'";
            }
            echo "><a href=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath((isset($context["route"]) ? $context["route"] : null)), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, (isset($context["label"]) ? $context["label"] : null), "html", null, true);
            echo "</a></li>
";
            echo trim(preg_replace('/>\s+</', '><', ob_get_clean()));
        } catch (Exception $e) {
            ob_end_clean();

            throw $e;
        }

        return ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
    }

    public function getTemplateName()
    {
        return "IcsePublicBundle:Default:template.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  202 => 3,  200 => 2,  186 => 1,  181 => 54,  176 => 55,  174 => 54,  168 => 52,  163 => 31,  158 => 26,  155 => 25,  139 => 22,  135 => 17,  132 => 16,  127 => 11,  109 => 52,  103 => 49,  90 => 46,  86 => 45,  65 => 35,  49 => 16,  37 => 12,  43 => 8,  29 => 8,  44 => 7,  25 => 6,  105 => 24,  96 => 21,  93 => 20,  83 => 18,  76 => 41,  72 => 14,  68 => 12,  58 => 31,  50 => 8,  41 => 14,  27 => 4,  24 => 3,  22 => 2,  19 => 1,  94 => 39,  88 => 6,  79 => 17,  59 => 22,  35 => 11,  31 => 9,  26 => 4,  21 => 1,  184 => 70,  178 => 66,  171 => 53,  165 => 58,  162 => 57,  157 => 56,  153 => 54,  151 => 53,  143 => 48,  138 => 45,  136 => 44,  133 => 43,  130 => 42,  122 => 37,  119 => 36,  116 => 60,  111 => 57,  108 => 31,  102 => 30,  98 => 47,  95 => 28,  92 => 27,  89 => 19,  85 => 24,  81 => 43,  73 => 19,  64 => 15,  60 => 13,  57 => 12,  54 => 29,  51 => 25,  48 => 14,  45 => 15,  42 => 6,  39 => 6,  36 => 5,  33 => 5,  30 => 3,);
    }
}
