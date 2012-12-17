<?php

/* IcsePublicBundle:Default:signup_results.html.twig */
class __TwigTemplate_359583c88877d2b2669c225bffd7ccc0 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("IcsePublicBundle:Default:template.html.twig");

        $this->blocks = array(
            'stylesheets' => array($this, 'block_stylesheets'),
            'aboveFooterAttrs' => array($this, 'block_aboveFooterAttrs'),
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
        // line 5
        $context["currentPage"] = "signup_results";
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 7
    public function block_stylesheets($context, array $blocks = array())
    {
        // line 8
        $this->displayParentBlock("stylesheets", $context, $blocks);
        echo "
<link rel=\"stylesheet\" type=\"text/css\" media=\"screen\" href=\"";
        // line 9
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/icsemembers/css/main.css"), "html", null, true);
        echo "\" />
";
    }

    // line 12
    public function block_aboveFooterAttrs($context, array $blocks = array())
    {
        echo "class=full_width";
    }

    // line 14
    public function block_title($context, array $blocks = array())
    {
        echo " - Signups";
    }

    // line 16
    public function block_content($context, array $blocks = array())
    {
        // line 17
        echo "
<p id=signupsfilter >
Show: 
  ";
        // line 20
        echo $this->getAttribute($this, "signup_filter", array(0 => (isset($context["currentShow"]) ? $context["currentShow"] : null), 1 => "All", 2 => "all"), "method");
        echo "
| ";
        // line 21
        echo $this->getAttribute($this, "signup_filter", array(0 => (isset($context["currentShow"]) ? $context["currentShow"] : null), 1 => "Concert-goers", 2 => "concertgoer"), "method");
        echo "
| ";
        // line 22
        echo $this->getAttribute($this, "signup_filter", array(0 => (isset($context["currentShow"]) ? $context["currentShow"] : null), 1 => "Players", 2 => "player"), "method");
        echo "
| ";
        // line 23
        echo $this->getAttribute($this, "signup_filter", array(0 => (isset($context["currentShow"]) ? $context["currentShow"] : null), 1 => "Violinists", 2 => "violin"), "method");
        echo "
| ";
        // line 24
        echo $this->getAttribute($this, "signup_filter", array(0 => (isset($context["currentShow"]) ? $context["currentShow"] : null), 1 => "Violists", 2 => "viola"), "method");
        echo "
| ";
        // line 25
        echo $this->getAttribute($this, "signup_filter", array(0 => (isset($context["currentShow"]) ? $context["currentShow"] : null), 1 => "Cellists", 2 => "cello"), "method");
        echo "
| ";
        // line 26
        echo $this->getAttribute($this, "signup_filter", array(0 => (isset($context["currentShow"]) ? $context["currentShow"] : null), 1 => "Double Bassists", 2 => "doublebass"), "method");
        echo "
| ";
        // line 27
        echo $this->getAttribute($this, "signup_filter", array(0 => (isset($context["currentShow"]) ? $context["currentShow"] : null), 1 => "Other Players", 2 => "otherplayer"), "method");
        echo "
</p>

<p>";
        // line 30
        echo twig_escape_filter($this->env, (isset($context["people_count"]) ? $context["people_count"] : null), "html", null, true);
        echo " people</p>

<p>
All e-mail addresses:
<textarea id=email_textarea readonly=\"readonly\" cols=50>";
        // line 34
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["signups"]) ? $context["signups"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["signup"]) {
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["signup"]) ? $context["signup"] : null), "getEmail", array(), "method"), "html", null, true);
            echo "
";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['signup'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 36
        echo "</textarea>
</p>

<script>
  \$(document).ready(function() {
      \$(\"#email_textarea\").focus(function() { \$(this).select(); } );
      \$(\"#email_textarea\").mouseup(function() { return false; } );
  }); 
</script>


<table id=signupstable>
  <tr>
    <th>Email</th>
    <th>Name</th>
    <th>Login</th>
    <th>Department</th>
    <th>Player?</th>
    <th>Instrument</th>
    <th>Standard</th>
    <th>Submitted at</th>
  </tr>
  
  ";
        // line 59
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["signups"]) ? $context["signups"] : null));
        $context['loop'] = array(
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        );
        if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof Countable)) {
            $length = count($context['_seq']);
            $context['loop']['revindex0'] = $length - 1;
            $context['loop']['revindex'] = $length;
            $context['loop']['length'] = $length;
            $context['loop']['last'] = 1 === $length;
        }
        foreach ($context['_seq'] as $context["_key"] => $context["signup"]) {
            // line 60
            echo "  <tr class=";
            echo twig_escape_filter($this->env, twig_cycle(array(0 => "odd", 1 => "even"), $this->getAttribute((isset($context["loop"]) ? $context["loop"] : null), "index")), "html", null, true);
            echo ">
    <td>";
            // line 61
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["signup"]) ? $context["signup"] : null), "getEmail", array(), "method"), "html", null, true);
            echo "</td>
    <td>";
            // line 62
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["signup"]) ? $context["signup"] : null), "getFirstName", array(), "method"), "html", null, true);
            echo " ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["signup"]) ? $context["signup"] : null), "getLastName", array(), "method"), "html", null, true);
            echo "</td>
    <td>";
            // line 63
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["signup"]) ? $context["signup"] : null), "getLogin", array(), "method"), "html", null, true);
            echo "</td>
    <td>";
            // line 64
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["signup"]) ? $context["signup"] : null), "getDepartment", array(), "method"), "html", null, true);
            echo "</td>
    <td>
      ";
            // line 66
            if ($this->getAttribute((isset($context["signup"]) ? $context["signup"] : null), "getPlayer", array(), "method")) {
                // line 67
                echo "        Yes
      ";
            } else {
                // line 69
                echo "        No
      ";
            }
            // line 71
            echo "    </td>
    <td>";
            // line 72
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["signup"]) ? $context["signup"] : null), "getInstrument", array(), "method"), "html", null, true);
            echo "</td>
    <td>";
            // line 73
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["signup"]) ? $context["signup"] : null), "getStandard", array(), "method"), "html", null, true);
            echo "</td>
    <td>";
            // line 74
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["signup"]) ? $context["signup"] : null), "getSubscribedAt", array(), "method"), "j/m/y H:i:s"), "html", null, true);
            echo "</td>
  </tr>
  ";
            ++$context['loop']['index0'];
            ++$context['loop']['index'];
            $context['loop']['first'] = false;
            if (isset($context['loop']['length'])) {
                --$context['loop']['revindex0'];
                --$context['loop']['revindex'];
                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['signup'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 77
        echo "</table>

";
    }

    // line 1
    public function getsignup_filter($_currentShow = null, $_label = null, $_id = null)
    {
        $context = $this->env->mergeGlobals(array(
            "currentShow" => $_currentShow,
            "label" => $_label,
            "id" => $_id,
        ));

        $blocks = array();

        ob_start();
        try {
            // line 2
            echo "<a ";
            if (((isset($context["id"]) ? $context["id"] : null) == (isset($context["currentShow"]) ? $context["currentShow"] : null))) {
                echo "class='current'";
            }
            echo " href=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("IcsePublicBundle_signup_results_tmp", array("show" => (isset($context["id"]) ? $context["id"] : null))), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, (isset($context["label"]) ? $context["label"] : null), "html", null, true);
            echo "</a>";
        } catch (Exception $e) {
            ob_end_clean();

            throw $e;
        }

        return ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
    }

    public function getTemplateName()
    {
        return "IcsePublicBundle:Default:signup_results.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  241 => 2,  228 => 1,  197 => 72,  190 => 69,  179 => 64,  175 => 63,  169 => 62,  107 => 34,  53 => 12,  52 => 14,  40 => 9,  301 => 100,  295 => 96,  292 => 95,  289 => 94,  287 => 93,  282 => 90,  276 => 86,  273 => 85,  270 => 84,  268 => 83,  263 => 80,  249 => 79,  245 => 77,  230 => 75,  222 => 77,  220 => 72,  215 => 70,  211 => 69,  204 => 66,  198 => 63,  185 => 61,  183 => 60,  177 => 58,  160 => 60,  149 => 51,  123 => 47,  120 => 46,  82 => 24,  38 => 6,  150 => 55,  144 => 50,  142 => 50,  129 => 45,  125 => 44,  117 => 42,  112 => 42,  106 => 35,  87 => 28,  70 => 21,  67 => 19,  61 => 17,  47 => 9,  91 => 20,  84 => 19,  74 => 22,  66 => 20,  55 => 13,  32 => 4,  28 => 5,  46 => 12,  225 => 96,  216 => 90,  212 => 88,  205 => 74,  201 => 73,  196 => 80,  194 => 71,  191 => 78,  189 => 77,  180 => 72,  172 => 67,  159 => 61,  154 => 59,  147 => 53,  121 => 45,  118 => 36,  114 => 43,  104 => 36,  100 => 30,  78 => 23,  75 => 23,  71 => 19,  63 => 18,  34 => 11,  202 => 3,  200 => 2,  186 => 67,  181 => 54,  176 => 55,  174 => 54,  168 => 52,  163 => 63,  158 => 58,  155 => 25,  139 => 22,  135 => 17,  132 => 48,  127 => 49,  109 => 41,  103 => 34,  90 => 26,  86 => 25,  65 => 16,  49 => 10,  37 => 6,  43 => 7,  29 => 4,  44 => 12,  25 => 4,  105 => 24,  96 => 21,  93 => 20,  83 => 18,  76 => 22,  72 => 28,  68 => 12,  58 => 16,  50 => 12,  41 => 9,  27 => 4,  24 => 2,  22 => 2,  19 => 1,  94 => 27,  88 => 27,  79 => 23,  59 => 15,  35 => 5,  31 => 4,  26 => 2,  21 => 2,  184 => 66,  178 => 71,  171 => 53,  165 => 61,  162 => 57,  157 => 56,  153 => 54,  151 => 52,  143 => 59,  138 => 47,  136 => 50,  133 => 43,  130 => 47,  122 => 37,  119 => 36,  116 => 60,  111 => 57,  108 => 31,  102 => 30,  98 => 34,  95 => 33,  92 => 28,  89 => 19,  85 => 24,  81 => 25,  73 => 19,  64 => 17,  60 => 23,  57 => 14,  54 => 10,  51 => 9,  48 => 13,  45 => 9,  42 => 8,  39 => 7,  36 => 8,  33 => 7,  30 => 7,);
    }
}
