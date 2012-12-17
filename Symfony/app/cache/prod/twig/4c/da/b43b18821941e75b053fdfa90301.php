<?php

/* DoctrineBundle:Collector:db.html.twig */
class __TwigTemplate_4cdab43b18821941e75b053fdfa90301 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->blocks = array(
            'toolbar' => array($this, 'block_toolbar'),
            'menu' => array($this, 'block_menu'),
            'panel' => array($this, 'block_panel'),
            'queries' => array($this, 'block_queries'),
        );
    }

    protected function doGetParent(array $context)
    {
        return $this->env->resolveTemplate((($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : null), "request"), "isXmlHttpRequest")) ? ("WebProfilerBundle:Profiler:ajax_layout.html.twig") : ("WebProfilerBundle:Profiler:layout.html.twig")));
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->getParent($context)->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_toolbar($context, array $blocks = array())
    {
        // line 4
        echo "    ";
        ob_start();
        // line 5
        echo "        <img width=\"20\" height=\"28\" alt=\"Database\" src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAcCAYAAABh2p9gAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAQRJREFUeNpi/P//PwM1ARMDlcGogZQDlpMnT7pxc3NbA9nhQKxOpL5rQLwJiPeBsI6Ozl+YBOOOHTv+AOllQNwtLS39F2owKYZ/gRq8G4i3ggxEToggWzvc3d2Pk+1lNL4fFAs6ODi8JzdS7mMRVyDVoAMHDsANdAPiOCC+jCQvQKqBQB/BDbwBxK5AHA3E/kB8nKJkA8TMQBwLxaBIKQbi70AvTADSBiSadwFXpCikpKQU8PDwkGTaly9fHFigkaKIJid4584dkiMFFI6jkTJII0WVmpHCAixZQEXWYhDeuXMnyLsVlEQKI45qFBQZ8eRECi4DBaAlDqle/8A48ip6gAADANdQY88Uc0oGAAAAAElFTkSuQmCC\"/>
        <span class=\"sf-toolbar-status";
        // line 6
        if ((50 < $this->getAttribute((isset($context["collector"]) ? $context["collector"] : null), "querycount"))) {
            echo " sf-toolbar-status-yellow";
        }
        echo "\">";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["collector"]) ? $context["collector"] : null), "querycount"), "html", null, true);
        echo "</span>
        <span class=\"sf-toolbar-info-piece-additional-detail\">in ";
        // line 7
        echo twig_escape_filter($this->env, sprintf("%0.2f", ($this->getAttribute((isset($context["collector"]) ? $context["collector"] : null), "time") * 1000)), "html", null, true);
        echo " ms</span>
    ";
        $context["icon"] = ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
        // line 9
        echo "    ";
        ob_start();
        // line 10
        echo "        <div class=\"sf-toolbar-info-piece\">
            <b>DB Queries</b>
            <span>";
        // line 12
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["collector"]) ? $context["collector"] : null), "querycount"), "html", null, true);
        echo "</span>
        </div>
        <div class=\"sf-toolbar-info-piece\">
            <b>Query time</b>
            <span>";
        // line 16
        echo twig_escape_filter($this->env, sprintf("%0.2f", ($this->getAttribute((isset($context["collector"]) ? $context["collector"] : null), "time") * 1000)), "html", null, true);
        echo " ms</span>
        </div>
    ";
        $context["text"] = ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
        // line 19
        echo "    ";
        $this->env->loadTemplate("WebProfilerBundle:Profiler:toolbar_item.html.twig")->display(array_merge($context, array("link" => (isset($context["profiler_url"]) ? $context["profiler_url"] : null))));
    }

    // line 22
    public function block_menu($context, array $blocks = array())
    {
        // line 23
        echo "<span class=\"label\">
    <span class=\"icon\"><img src=\"";
        // line 24
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/webprofiler/images/profiler/db.png"), "html", null, true);
        echo "\" alt=\"\" /></span>
    <strong>Doctrine</strong>
    <span class=\"count\">
        <span>";
        // line 27
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["collector"]) ? $context["collector"] : null), "querycount"), "html", null, true);
        echo "</span>
        <span>";
        // line 28
        echo twig_escape_filter($this->env, sprintf("%0.0f", ($this->getAttribute((isset($context["collector"]) ? $context["collector"] : null), "time") * 1000)), "html", null, true);
        echo " ms</span>
    </span>
</span>
";
    }

    // line 33
    public function block_panel($context, array $blocks = array())
    {
        // line 34
        echo "    ";
        if (("explain" == (isset($context["page"]) ? $context["page"] : null))) {
            // line 35
            echo "        ";
            echo $this->env->getExtension('actions')->renderAction("DoctrineBundle:Profiler:explain", array("token" => (isset($context["token"]) ? $context["token"] : null), "panel" => "db", "connectionName" => $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : null), "request"), "query"), "get", array(0 => "connection"), "method"), "query" => $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : null), "request"), "query"), "get", array(0 => "query"), "method")), array());
            // line 41
            echo "    ";
        } else {
            // line 42
            echo "        ";
            $this->displayBlock("queries", $context, $blocks);
            echo "
    ";
        }
    }

    // line 46
    public function block_queries($context, array $blocks = array())
    {
        // line 47
        echo "    <h2>Queries</h2>

    ";
        // line 49
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["collector"]) ? $context["collector"] : null), "queries"));
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
        foreach ($context['_seq'] as $context["connection"] => $context["queries"]) {
            // line 50
            echo "        <h3>Connection <em>";
            echo twig_escape_filter($this->env, (isset($context["connection"]) ? $context["connection"] : null), "html", null, true);
            echo "</em></h3>
        ";
            // line 51
            if (twig_test_empty((isset($context["queries"]) ? $context["queries"] : null))) {
                // line 52
                echo "            <p>
                <em>No queries.</em>
            </p>
        ";
            } else {
                // line 56
                echo "            <ul class=\"alt\">
                ";
                // line 57
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable((isset($context["queries"]) ? $context["queries"] : null));
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
                foreach ($context['_seq'] as $context["i"] => $context["query"]) {
                    // line 58
                    echo "                    <li class=\"";
                    echo twig_escape_filter($this->env, twig_cycle(array(0 => "odd", 1 => "even"), (isset($context["i"]) ? $context["i"] : null)), "html", null, true);
                    echo "\">
                        <div>
                            ";
                    // line 60
                    if ($this->getAttribute((isset($context["query"]) ? $context["query"] : null), "explainable")) {
                        // line 61
                        echo "                            <a href=\"";
                        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("_profiler", array("panel" => "db", "token" => (isset($context["token"]) ? $context["token"] : null), "page" => "explain", "connection" => (isset($context["connection"]) ? $context["connection"] : null), "query" => (isset($context["i"]) ? $context["i"] : null))), "html", null, true);
                        echo "\" onclick=\"return explain(this);\" style=\"text-decoration: none;\" title=\"Explains\" data-target-id=\"explain-";
                        echo twig_escape_filter($this->env, (isset($context["i"]) ? $context["i"] : null), "html", null, true);
                        echo "-";
                        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["loop"]) ? $context["loop"] : null), "parent"), "loop"), "index"), "html", null, true);
                        echo "\" >
                                <img alt=\"+\" src=\"";
                        // line 62
                        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/framework/images/blue_picto_more.gif"), "html", null, true);
                        echo "\" style=\"display: inline;\" />
                                <img alt=\"-\" src=\"";
                        // line 63
                        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/framework/images/blue_picto_less.gif"), "html", null, true);
                        echo "\" style=\"display: none;\" />
                            </a>
                            ";
                    }
                    // line 66
                    echo "                            <code>";
                    echo twig_escape_filter($this->env, $this->getAttribute((isset($context["query"]) ? $context["query"] : null), "sql"), "html", null, true);
                    echo "</code>
                        </div>
                        <small>
                            <strong>Parameters</strong>: ";
                    // line 69
                    echo twig_escape_filter($this->env, $this->env->getExtension('yaml')->encode($this->getAttribute((isset($context["query"]) ? $context["query"] : null), "params")), "html", null, true);
                    echo "<br />
                            <strong>Time</strong>: ";
                    // line 70
                    echo twig_escape_filter($this->env, sprintf("%0.2f", ($this->getAttribute((isset($context["query"]) ? $context["query"] : null), "executionMS") * 1000)), "html", null, true);
                    echo " ms
                        </small>
                        ";
                    // line 72
                    if ($this->getAttribute((isset($context["query"]) ? $context["query"] : null), "explainable")) {
                        // line 73
                        echo "                        <div id=\"explain-";
                        echo twig_escape_filter($this->env, (isset($context["i"]) ? $context["i"] : null), "html", null, true);
                        echo "-";
                        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["loop"]) ? $context["loop"] : null), "parent"), "loop"), "index"), "html", null, true);
                        echo "\" class=\"loading\"></div>
                        ";
                    }
                    // line 75
                    echo "                    </li>
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
                unset($context['_seq'], $context['_iterated'], $context['i'], $context['query'], $context['_parent'], $context['loop']);
                $context = array_merge($_parent, array_intersect_key($context, $_parent));
                // line 77
                echo "            </ul>
        ";
            }
            // line 79
            echo "    ";
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
        unset($context['_seq'], $context['_iterated'], $context['connection'], $context['queries'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 80
        echo "
    <h2>Database Connections</h2>

    ";
        // line 83
        if ($this->getAttribute((isset($context["collector"]) ? $context["collector"] : null), "connections")) {
            // line 84
            echo "        ";
            $this->env->loadTemplate("WebProfilerBundle:Profiler:table.html.twig")->display(array("data" => $this->getAttribute((isset($context["collector"]) ? $context["collector"] : null), "connections")));
            // line 85
            echo "    ";
        } else {
            // line 86
            echo "        <p>
            <em>No connections.</em>
        </p>
    ";
        }
        // line 90
        echo "
    <h2>Entity Managers</h2>

    ";
        // line 93
        if ($this->getAttribute((isset($context["collector"]) ? $context["collector"] : null), "managers")) {
            // line 94
            echo "        ";
            $this->env->loadTemplate("WebProfilerBundle:Profiler:table.html.twig")->display(array("data" => $this->getAttribute((isset($context["collector"]) ? $context["collector"] : null), "managers")));
            // line 95
            echo "    ";
        } else {
            // line 96
            echo "        <p>
            <em>No entity managers.</em>
        </p>
    ";
        }
        // line 100
        echo "
    <script type=\"text/javascript\">//<![CDATA[
        function explain(link) {
            \"use strict\";

            var imgs = link.children,
                target = link.getAttribute('data-target-id');

            Sfjs.toggle(target, imgs[0], imgs[1])
                .load(
                    target,
                    link.href,
                    null,
                    function(xhr, el) {
                        el.innerHTML = 'An error occurred while loading the details';
                        Sfjs.removeClass(el, 'loading');
                    }
                );

            return false;
        }
    //]]></script>
";
    }

    public function getTemplateName()
    {
        return "DoctrineBundle:Collector:db.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  301 => 100,  295 => 96,  292 => 95,  289 => 94,  287 => 93,  282 => 90,  276 => 86,  273 => 85,  270 => 84,  268 => 83,  263 => 80,  249 => 79,  245 => 77,  230 => 75,  222 => 73,  220 => 72,  215 => 70,  211 => 69,  204 => 66,  198 => 63,  185 => 61,  183 => 60,  177 => 58,  160 => 57,  149 => 51,  123 => 47,  120 => 46,  82 => 24,  38 => 6,  150 => 55,  144 => 50,  142 => 50,  129 => 45,  125 => 44,  117 => 42,  112 => 42,  106 => 35,  87 => 28,  70 => 20,  67 => 19,  61 => 16,  47 => 9,  91 => 20,  84 => 19,  74 => 16,  66 => 15,  55 => 15,  32 => 4,  28 => 3,  46 => 7,  225 => 96,  216 => 90,  212 => 88,  205 => 84,  201 => 83,  196 => 80,  194 => 62,  191 => 78,  189 => 77,  180 => 72,  172 => 67,  159 => 61,  154 => 59,  147 => 53,  121 => 45,  118 => 44,  114 => 43,  104 => 36,  100 => 33,  78 => 24,  75 => 23,  71 => 19,  63 => 24,  34 => 11,  202 => 3,  200 => 2,  186 => 76,  181 => 54,  176 => 55,  174 => 54,  168 => 52,  163 => 63,  158 => 58,  155 => 25,  139 => 22,  135 => 17,  132 => 48,  127 => 49,  109 => 41,  103 => 34,  90 => 46,  86 => 45,  65 => 16,  49 => 16,  37 => 12,  43 => 7,  29 => 3,  44 => 12,  25 => 4,  105 => 24,  96 => 21,  93 => 20,  83 => 18,  76 => 22,  72 => 14,  68 => 12,  58 => 12,  50 => 10,  41 => 9,  27 => 4,  24 => 3,  22 => 2,  19 => 1,  94 => 39,  88 => 27,  79 => 23,  59 => 22,  35 => 5,  31 => 5,  26 => 6,  21 => 2,  184 => 70,  178 => 71,  171 => 53,  165 => 58,  162 => 57,  157 => 56,  153 => 54,  151 => 52,  143 => 54,  138 => 47,  136 => 50,  133 => 43,  130 => 47,  122 => 37,  119 => 36,  116 => 60,  111 => 57,  108 => 31,  102 => 30,  98 => 34,  95 => 33,  92 => 28,  89 => 19,  85 => 24,  81 => 25,  73 => 19,  64 => 17,  60 => 23,  57 => 14,  54 => 10,  51 => 9,  48 => 13,  45 => 15,  42 => 7,  39 => 9,  36 => 5,  33 => 4,  30 => 7,);
    }
}
