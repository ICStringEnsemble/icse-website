<?php

/* IcseMembersBundle:Default:template.html.twig */
class __TwigTemplate_1c355521061a952168539932389a7a9f extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("IcsePublicBundle:Default:template.html.twig");

        $this->blocks = array(
            'stylesheets' => array($this, 'block_stylesheets'),
            'javascripts' => array($this, 'block_javascripts'),
            'header_to_footer' => array($this, 'block_header_to_footer'),
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "IcsePublicBundle:Default:template.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 2
        $context["currentPage"] = "members";
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 4
    public function block_stylesheets($context, array $blocks = array())
    {
        // line 5
        $this->displayParentBlock("stylesheets", $context, $blocks);
        echo "
";
        // line 6
        if (isset($context['assetic']['debug']) && $context['assetic']['debug']) {
            // asset "ec96d3b_0"
            $context["asset_url"] = isset($context['assetic']['use_controller']) && $context['assetic']['use_controller'] ? $this->env->getExtension('routing')->getPath("_assetic_ec96d3b_0") : $this->env->getExtension('assets')->getAssetUrl("bundles/icsemembers/css/main_main_1.css");
            // line 12
            echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"screen\" href=\"";
            echo twig_escape_filter($this->env, (isset($context["asset_url"]) ? $context["asset_url"] : null), "html", null, true);
            echo "\" />
";
            // asset "ec96d3b_1"
            $context["asset_url"] = isset($context['assetic']['use_controller']) && $context['assetic']['use_controller'] ? $this->env->getExtension('routing')->getPath("_assetic_ec96d3b_1") : $this->env->getExtension('assets')->getAssetUrl("bundles/icsemembers/css/main_upload_2.css");
            echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"screen\" href=\"";
            echo twig_escape_filter($this->env, (isset($context["asset_url"]) ? $context["asset_url"] : null), "html", null, true);
            echo "\" />
";
        } else {
            // asset "ec96d3b"
            $context["asset_url"] = isset($context['assetic']['use_controller']) && $context['assetic']['use_controller'] ? $this->env->getExtension('routing')->getPath("_assetic_ec96d3b") : $this->env->getExtension('assets')->getAssetUrl("bundles/icsemembers/css/main.css");
            echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"screen\" href=\"";
            echo twig_escape_filter($this->env, (isset($context["asset_url"]) ? $context["asset_url"] : null), "html", null, true);
            echo "\" />
";
        }
        unset($context["asset_url"]);
        // line 13
        echo " 
<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"//ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/themes/humanity/jquery-ui.css\" /> 
";
    }

    // line 17
    public function block_javascripts($context, array $blocks = array())
    {
        // line 18
        $this->displayParentBlock("javascripts", $context, $blocks);
        echo "
<script src=\"//ajax.googleapis.com/ajax/libs/jqueryui/1.9.0/jquery-ui.min.js\"></script> 
";
    }

    // line 26
    public function block_header_to_footer($context, array $blocks = array())
    {
        // line 27
        echo "<div id=\"nav_column\">
  <div id=\"hidden_nav_col_edge\"><div>Menu</div></div>
  <ul>
    ";
        // line 31
        echo "    <li id=\"nav_col_head\">Members Area<img title=\"Hide/Show\" id=\"hide_nav_col_button\" src=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/icsemembers/images/leftarrow.png"), "html", null, true);
        echo "\" /></li>
    <li><a href=\"#\">Schedule</a></li>
    <li><a href=\"#\">Practice Parts</a></li>

    ";
        // line 36
        echo "    ";
        if ($this->env->getExtension('security')->isGranted("ROLE_ADMIN")) {
            // line 37
            echo "    <li class=\"category\">Admin</li>
    <li><a href=\"#\">Members List</a></li>
    <li><a href=\"#\">Music Library</a></li>
    <li><a href=\"";
            // line 40
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("IcseMembersBundle_image_uploads"), "html", null, true);
            echo "\">Photos and Images</a></li>
    <li><a href=\"#\">Site Text</a></li>
    ";
        }
        // line 43
        echo "
    ";
        // line 45
        echo "    ";
        if ($this->env->getExtension('security')->isGranted("ROLE_SUPER_ADMIN")) {
            // line 46
            echo "    <li class=\"category\">Super Admin</li>
    <div id=\"confirm_migrateDB\" title=\"Perform Database Migration?\" hidden=hidden>
      <p><span class=\"ui-icon ui-icon-alert\" style=\"float:left; margin:0 7px 0 0;\"></span>
      WARNING! You are about to execute a database migration that could result in schema
      changes and data lost. Are you sure you wish to continue?</p>
    </div>
    <li><a href=\"\" id=\"migrateDB\">Migrate DB</a></li>
    <script>
      \$('#confirm_migrateDB').dialog({
        autoOpen: false,
        resizable: false,
        modal: true,
        buttons : {
          \"Yes\" : function(){window.location.href = \"";
            // line 59
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("IcseMembersBundle_migrateDB"), "html", null, true);
            echo "\";},
          \"No\" : function(){\$(this).dialog(\"close\");}
        }
      });

      \$('#migrateDB').click(function(e){
        e.preventDefault(); 
        \$('#confirm_migrateDB').dialog('open');
      })
    </script>
    ";
        }
        // line 70
        echo "
    ";
        // line 72
        echo "    <li class=\"category\">Account: ";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : null), "user"), "getFullName", array(), "method"), "html", null, true);
        echo "</li>
    <li><a href=\"";
        // line 73
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("IcseMembersBundle_account_settings"), "html", null, true);
        echo "\">Settings</a></li>
    <li><a href=\"";
        // line 74
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("IcseMembersBundle_logout"), "html", null, true);
        echo "\">Logout</a></li>
  </ul>
</div>
<div id=\"section_container\">
<section>
";
        // line 79
        $this->displayBlock('content', $context, $blocks);
        // line 81
        echo "</section>
</div>
<script>
  \$(document).ready(function(){
    var nav_col_enabled = true;
    \$('#hide_nav_col_button, #hidden_nav_col_edge').click(function(){
      if (nav_col_enabled) {
        \$('#above_footer').addClass(\"nav_col_hidden\"); 
        \$('#hide_nav_col_button').addClass(\"rotate-180\");
        nav_col_enabled = false;
      }
      else {
        \$('#above_footer').removeClass(\"nav_col_hidden\"); 
        \$('#hide_nav_col_button').removeClass(\"rotate-180\");
        nav_col_enabled = true;
      }
    });
  })
</script>
";
    }

    // line 79
    public function block_content($context, array $blocks = array())
    {
    }

    public function getTemplateName()
    {
        return "IcseMembersBundle:Default:template.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  167 => 81,  148 => 72,  145 => 70,  131 => 59,  113 => 45,  80 => 26,  110 => 43,  99 => 37,  56 => 22,  101 => 40,  278 => 111,  269 => 108,  266 => 107,  264 => 106,  253 => 97,  244 => 95,  240 => 94,  217 => 73,  208 => 71,  195 => 67,  192 => 66,  140 => 49,  134 => 46,  124 => 51,  241 => 2,  228 => 1,  197 => 72,  190 => 79,  179 => 64,  175 => 61,  169 => 62,  107 => 29,  53 => 12,  52 => 14,  40 => 6,  301 => 100,  295 => 96,  292 => 95,  289 => 94,  287 => 93,  282 => 118,  276 => 86,  273 => 109,  270 => 84,  268 => 83,  263 => 80,  249 => 79,  245 => 77,  230 => 75,  222 => 77,  220 => 72,  215 => 70,  211 => 72,  204 => 66,  198 => 63,  185 => 61,  183 => 60,  177 => 58,  160 => 60,  149 => 52,  123 => 47,  120 => 46,  82 => 21,  38 => 7,  150 => 71,  144 => 50,  142 => 50,  129 => 45,  125 => 44,  117 => 30,  112 => 31,  106 => 35,  87 => 28,  70 => 17,  67 => 33,  61 => 17,  47 => 9,  91 => 36,  84 => 19,  74 => 19,  66 => 20,  55 => 13,  32 => 6,  28 => 2,  46 => 13,  225 => 96,  216 => 90,  212 => 88,  205 => 74,  201 => 69,  196 => 80,  194 => 71,  191 => 78,  189 => 77,  180 => 72,  172 => 67,  159 => 61,  154 => 54,  147 => 53,  121 => 42,  118 => 36,  114 => 47,  104 => 40,  100 => 30,  78 => 20,  75 => 29,  71 => 18,  63 => 18,  34 => 11,  202 => 3,  200 => 2,  186 => 63,  181 => 54,  176 => 55,  174 => 54,  168 => 59,  163 => 57,  158 => 58,  155 => 25,  139 => 22,  135 => 59,  132 => 48,  127 => 45,  109 => 39,  103 => 34,  90 => 26,  86 => 10,  65 => 28,  49 => 9,  37 => 6,  43 => 7,  29 => 3,  44 => 12,  25 => 4,  105 => 31,  96 => 36,  93 => 20,  83 => 27,  76 => 22,  72 => 36,  68 => 12,  58 => 16,  50 => 13,  41 => 8,  27 => 4,  24 => 1,  22 => 2,  19 => 1,  94 => 27,  88 => 31,  79 => 30,  59 => 15,  35 => 6,  31 => 4,  26 => 2,  21 => 2,  184 => 66,  178 => 71,  171 => 60,  165 => 79,  162 => 57,  157 => 74,  153 => 73,  151 => 52,  143 => 59,  138 => 47,  136 => 50,  133 => 43,  130 => 47,  122 => 37,  119 => 49,  116 => 46,  111 => 57,  108 => 44,  102 => 30,  98 => 28,  95 => 37,  92 => 28,  89 => 11,  85 => 33,  81 => 5,  73 => 18,  64 => 13,  60 => 23,  57 => 14,  54 => 14,  51 => 9,  48 => 16,  45 => 8,  42 => 7,  39 => 6,  36 => 5,  33 => 4,  30 => 5,);
    }
}
