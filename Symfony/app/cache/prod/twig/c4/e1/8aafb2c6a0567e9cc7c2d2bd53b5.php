<?php

/* IcsePublicBundle:Default:home.html.twig */
class __TwigTemplate_c4e18aafb2c6a0567e9cc7c2d2bd53b5 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("IcsePublicBundle:Default:template.html.twig");

        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'stylesheets' => array($this, 'block_stylesheets'),
            'javascripts' => array($this, 'block_javascripts'),
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
        $context["currentPage"] = "home";
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 4
    public function block_title($context, array $blocks = array())
    {
        echo " - Home";
    }

    // line 6
    public function block_stylesheets($context, array $blocks = array())
    {
        // line 7
        $this->displayParentBlock("stylesheets", $context, $blocks);
        echo "
";
        // line 8
        if (isset($context['assetic']['debug']) && $context['assetic']['debug']) {
            // asset "83564f2_0"
            $context["asset_url"] = isset($context['assetic']['use_controller']) && $context['assetic']['use_controller'] ? $this->env->getExtension('routing')->getPath("_assetic_83564f2_0") : $this->env->getExtension('assets')->getAssetUrl("bundles/icsepublic/css/home_home_1.css");
            // line 13
            echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"screen\" href=\"";
            echo twig_escape_filter($this->env, (isset($context["asset_url"]) ? $context["asset_url"] : null), "html", null, true);
            echo "\" />
";
        } else {
            // asset "83564f2"
            $context["asset_url"] = isset($context['assetic']['use_controller']) && $context['assetic']['use_controller'] ? $this->env->getExtension('routing')->getPath("_assetic_83564f2") : $this->env->getExtension('assets')->getAssetUrl("bundles/icsepublic/css/home.css");
            echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"screen\" href=\"";
            echo twig_escape_filter($this->env, (isset($context["asset_url"]) ? $context["asset_url"] : null), "html", null, true);
            echo "\" />
";
        }
        unset($context["asset_url"]);
        // line 15
        echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"screen\" href=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/icsepublic/fancybox/jquery.fancybox.css"), "html", null, true);
        echo "\" />
";
    }

    // line 18
    public function block_javascripts($context, array $blocks = array())
    {
        // line 19
        $this->displayParentBlock("javascripts", $context, $blocks);
        echo "
<script src=\"";
        // line 20
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/icsepublic/js/jquery.cycle.all.js"), "html", null, true);
        echo "\"></script> 
<script src=\"";
        // line 21
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/icsepublic/fancybox/jquery.fancybox.js"), "html", null, true);
        echo "\"></script> 
";
    }

    // line 24
    public function block_content($context, array $blocks = array())
    {
        // line 25
        echo "
<div id=col_left>
  ";
        // line 27
        echo " 
  ";
        // line 28
        echo (isset($context["home_intro"]) ? $context["home_intro"] : null);
        echo "
  ";
        // line 30
        echo "
  ";
        // line 31
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable(array(0 => array("label" => "Today", "events" => (isset($context["today_events"]) ? $context["today_events"] : null)), 1 => array("label" => "Tomorrow", "events" => (isset($context["tomorrow_events"]) ? $context["tomorrow_events"] : null)), 2 => array("label" => ((((isset($context["today_events"]) ? $context["today_events"] : null) || (isset($context["tomorrow_events"]) ? $context["tomorrow_events"] : null))) ? ("Later This Week") : ("This Week")), "events" => (isset($context["thisweek_events"]) ? $context["thisweek_events"] : null)), 3 => array("label" => "Next Week", "events" => (isset($context["nextweek_events"]) ? $context["nextweek_events"] : null)), 4 => array("label" => ((((((isset($context["today_events"]) ? $context["today_events"] : null) || (isset($context["tomorrow_events"]) ? $context["tomorrow_events"] : null)) || (isset($context["thisweek_events"]) ? $context["thisweek_events"] : null)) || (isset($context["nextweek_events"]) ? $context["nextweek_events"] : null))) ? ("Other Upcoming Events") : ("Upcoming Events")), "events" => (isset($context["future_events"]) ? $context["future_events"] : null)), 5 => array("label" => "Previous Events", "events" => (isset($context["past_events"]) ? $context["past_events"] : null))));
        foreach ($context['_seq'] as $context["_key"] => $context["event_group"]) {
            // line 39
            echo "    ";
            if ($this->getAttribute((isset($context["event_group"]) ? $context["event_group"] : null), "events")) {
                // line 40
                echo "      <h2>";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["event_group"]) ? $context["event_group"] : null), "label"), "html", null, true);
                echo "</h2>
      ";
                // line 41
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["event_group"]) ? $context["event_group"] : null), "events"));
                foreach ($context['_seq'] as $context["_key"] => $context["event"]) {
                    // line 42
                    echo "        <div class=event >
          ";
                    // line 44
                    echo "          ";
                    if ($this->getAttribute((isset($context["event"]) ? $context["event"] : null), "getPoster", array(), "method")) {
                        // line 45
                        echo "          <a class=\"view_full_image\" href=\"";
                        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("IcsePublicBundle_resource", array("type" => "images", "file" => $this->getAttribute($this->getAttribute((isset($context["event"]) ? $context["event"] : null), "getPoster", array(), "method"), "getFile", array(), "method"), "size" => "original")), "html", null, true);
                        echo "\" title=\"";
                        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["event"]) ? $context["event"] : null), "getName", array(), "method"), "html", null, true);
                        echo "\" >
            <img class=poster src=\"";
                        // line 46
                        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("IcsePublicBundle_resource", array("type" => "images", "file" => $this->getAttribute($this->getAttribute((isset($context["event"]) ? $context["event"] : null), "getPoster", array(), "method"), "getFile", array(), "method"), "size" => "hpmainthumb")), "html", null, true);
                        echo "\" />
          </a>
          ";
                    }
                    // line 49
                    echo "          ";
                    // line 50
                    echo "          <h3><a href=\"";
                    echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("IcsePublicBundle_event", (isset($context["event"]) ? $context["event"] : null)), "html", null, true);
                    echo "\">";
                    echo twig_escape_filter($this->env, $this->getAttribute((isset($context["event"]) ? $context["event"] : null), "getName", array(), "method"), "html", null, true);
                    echo "</a></h3>
          ";
                    // line 52
                    echo "          <div class=\"datetime\">";
                    echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["event"]) ? $context["event"] : null), "getStartsAt", array(), "method"), "l jS F Y, g:ia"), "html", null, true);
                    echo "</div>
          ";
                    // line 54
                    echo "          ";
                    if ($this->getAttribute((isset($context["event"]) ? $context["event"] : null), "getLocation", array(), "method")) {
                        // line 55
                        echo "            <div class=\"location\">";
                        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["event"]) ? $context["event"] : null), "getLocation", array(), "method"), "getName", array(), "method"), "html", null, true);
                        echo "</div>
          ";
                    }
                    // line 57
                    echo "          ";
                    // line 58
                    echo "          ";
                    if ((!$this->getAttribute($this->getAttribute((isset($context["event"]) ? $context["event"] : null), "getPerformances", array(), "method"), "isEmpty", array(), "method"))) {
                        // line 59
                        echo "          <ul class=\"repertoire\">
            ";
                        // line 60
                        $context['_parent'] = (array) $context;
                        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["event"]) ? $context["event"] : null), "getPerformances", array(), "method"));
                        foreach ($context['_seq'] as $context["_key"] => $context["performance"]) {
                            // line 61
                            echo "            <li>";
                            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["performance"]) ? $context["performance"] : null), "getPiece", array(), "method"), "getComposer", array(), "method"), "html", null, true);
                            echo ": ";
                            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["performance"]) ? $context["performance"] : null), "getPiece", array(), "method"), "getName", array(), "method"), "html", null, true);
                            echo "</li>
            ";
                        }
                        $_parent = $context['_parent'];
                        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['performance'], $context['_parent'], $context['loop']);
                        $context = array_merge($_parent, array_intersect_key($context, $_parent));
                        // line 63
                        echo "          </ul>
          ";
                    }
                    // line 65
                    echo "          ";
                    // line 66
                    echo "          ";
                    if ($this->getAttribute((isset($context["event"]) ? $context["event"] : null), "getDescription", array(), "method")) {
                        // line 67
                        echo "            <p>";
                        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["event"]) ? $context["event"] : null), "getDescription", array(), "method"), "html", null, true);
                        echo "</p>
          ";
                    }
                    // line 69
                    echo "        </div>
      ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['event'], $context['_parent'], $context['loop']);
                $context = array_merge($_parent, array_intersect_key($context, $_parent));
                // line 71
                echo "    ";
            }
            // line 72
            echo "  ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['event_group'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 73
        echo "</div>

<script>
  \$('.view_full_image').fancybox({
    padding: 0,
    closeClick: true,
    openEffect:'elastic',
    closeEffect:'elastic',
    helpers: {
      orig: \$(this),
      overlay: {
        css: {
          'background': 'rgba(45, 45, 45, 0.5)' 
        }
      }
    }
  });
</script>

<div id=col_right>
  <div id=slideshow >
    ";
        // line 94
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["slideshow_images"]) ? $context["slideshow_images"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["image"]) {
            // line 95
            echo "    <img width=334px height=254px src=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("IcsePublicBundle_resource", array("type" => "images", "file" => $this->getAttribute((isset($context["image"]) ? $context["image"] : null), "getFile", array(), "method"), "size" => "hpslideshow")), "html", null, true);
            echo "\"/>
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['image'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 97
        echo "  </div>
  <script>
    \$('#slideshow').cycle({ 
        fx:     'fadeout', 
        random:  1 
    }); 
  </script>


  ";
        // line 106
        if ((isset($context["next_rehearsal"]) ? $context["next_rehearsal"] : null)) {
            // line 107
            echo "  <h2>Next Rehearsal</h2>
  <div class=\"datetime\">";
            // line 108
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["next_rehearsal"]) ? $context["next_rehearsal"] : null), "getStartsAt", array(), "method"), "l jS F Y, g:ia"), "html", null, true);
            echo "</div>
  <div class=\"location\">";
            // line 109
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["next_rehearsal"]) ? $context["next_rehearsal"] : null), "getLocation", array(), "method"), "getName", array(), "method"), "html", null, true);
            echo "</div>
  ";
        }
        // line 111
        echo "
  <h2>News</h2>
  ";
        // line 118
        echo "  <p>Coming soon...</p>

  <h2>Twitter</h2>

  <a class=\"twitter-timeline\" href=\"https://twitter.com/ICStrings\" data-widget-id=\"248604844275929088\">Tweets by @ICStrings</a>
  <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=\"//platform.twitter.com/widgets.js\";fjs.parentNode.insertBefore(js,fjs);}}(document,\"script\",\"twitter-wjs\");</script>
 
</div>

";
    }

    public function getTemplateName()
    {
        return "IcsePublicBundle:Default:home.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  278 => 111,  269 => 108,  266 => 107,  264 => 106,  253 => 97,  244 => 95,  240 => 94,  217 => 73,  208 => 71,  195 => 67,  192 => 66,  140 => 49,  134 => 46,  124 => 44,  241 => 2,  228 => 1,  197 => 72,  190 => 65,  179 => 64,  175 => 61,  169 => 62,  107 => 34,  53 => 12,  52 => 14,  40 => 9,  301 => 100,  295 => 96,  292 => 95,  289 => 94,  287 => 93,  282 => 118,  276 => 86,  273 => 109,  270 => 84,  268 => 83,  263 => 80,  249 => 79,  245 => 77,  230 => 75,  222 => 77,  220 => 72,  215 => 70,  211 => 72,  204 => 66,  198 => 63,  185 => 61,  183 => 60,  177 => 58,  160 => 60,  149 => 52,  123 => 47,  120 => 46,  82 => 21,  38 => 6,  150 => 55,  144 => 50,  142 => 50,  129 => 45,  125 => 44,  117 => 41,  112 => 40,  106 => 35,  87 => 28,  70 => 21,  67 => 19,  61 => 17,  47 => 9,  91 => 25,  84 => 19,  74 => 19,  66 => 20,  55 => 13,  32 => 4,  28 => 2,  46 => 8,  225 => 96,  216 => 90,  212 => 88,  205 => 74,  201 => 69,  196 => 80,  194 => 71,  191 => 78,  189 => 77,  180 => 72,  172 => 67,  159 => 61,  154 => 54,  147 => 53,  121 => 42,  118 => 36,  114 => 43,  104 => 36,  100 => 30,  78 => 20,  75 => 23,  71 => 18,  63 => 18,  34 => 11,  202 => 3,  200 => 2,  186 => 63,  181 => 54,  176 => 55,  174 => 54,  168 => 59,  163 => 57,  158 => 58,  155 => 25,  139 => 22,  135 => 17,  132 => 48,  127 => 45,  109 => 39,  103 => 34,  90 => 26,  86 => 25,  65 => 16,  49 => 10,  37 => 6,  43 => 7,  29 => 4,  44 => 12,  25 => 4,  105 => 31,  96 => 21,  93 => 20,  83 => 18,  76 => 22,  72 => 28,  68 => 12,  58 => 16,  50 => 13,  41 => 9,  27 => 4,  24 => 2,  22 => 2,  19 => 1,  94 => 27,  88 => 24,  79 => 23,  59 => 15,  35 => 5,  31 => 4,  26 => 2,  21 => 2,  184 => 66,  178 => 71,  171 => 60,  165 => 58,  162 => 57,  157 => 55,  153 => 54,  151 => 52,  143 => 59,  138 => 47,  136 => 50,  133 => 43,  130 => 47,  122 => 37,  119 => 36,  116 => 60,  111 => 57,  108 => 31,  102 => 30,  98 => 28,  95 => 27,  92 => 28,  89 => 19,  85 => 24,  81 => 25,  73 => 19,  64 => 15,  60 => 23,  57 => 14,  54 => 10,  51 => 9,  48 => 13,  45 => 9,  42 => 7,  39 => 6,  36 => 8,  33 => 4,  30 => 7,);
    }
}
