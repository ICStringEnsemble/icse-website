<?php

/* SwiftmailerBundle:Collector:swiftmailer.html.twig */
class __TwigTemplate_d574671a2c975cc74400bdbc4e00c5de extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("WebProfilerBundle:Profiler:layout.html.twig");

        $this->blocks = array(
            'toolbar' => array($this, 'block_toolbar'),
            'menu' => array($this, 'block_menu'),
            'panel' => array($this, 'block_panel'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "WebProfilerBundle:Profiler:layout.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_toolbar($context, array $blocks = array())
    {
        // line 4
        echo "    ";
        if ($this->getAttribute((isset($context["collector"]) ? $context["collector"] : null), "messagecount")) {
            // line 5
            echo "        ";
            ob_start();
            // line 6
            echo "            <img width=\"23\" height=\"28\" alt=\"Swiftmailer\" src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABcAAAAcCAYAAACK7SRjAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNSBNYWNpbnRvc2giIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6N0NEOTU1MjM0OThFMTFFMDg3NzJBNTE2ODgwQzMxMzQiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6N0NEOTU1MjQ0OThFMTFFMDg3NzJBNTE2ODgwQzMxMzQiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDoxMEQ5OTQ5QzQ5OEMxMUUwODc3MkE1MTY4ODBDMzEzNCIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo3Q0Q5NTUyMjQ5OEUxMUUwODc3MkE1MTY4ODBDMzEzNCIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PpkRnSAAAAJ0SURBVHjaYvz//z8DrQATAw3BqOFYAaO9vT1FBhw4cGCAXA5MipxBQUHT3r17l0AVAxkZ/wkLC89as2ZNIcjlYkALXKnlWqBZTH/+/PEDmQsynLW/v3+NoaHhN2oYDjJn8uTJK4BMNpDhPwsLCwOKiop2+fn5vafEYC8vrw8gc/Lz8wOB3B8gw/nev38vn5WV5eTg4LA/Ly/vESsrK2npmYmJITU19SnQ8L0gc4DxpwgyF2S4EEjB58+f+crLy31YWFjOt7S0XBYUFPxHjMEcHBz/6+rqboqJiZ0qKSnxBpkDlRICGc4MU/j792+2CRMm+L18+fLSxIkTDykoKPzBZ7CoqOi/np6eE8rKylvb29v9fvz4wYEkzYKRzjk5OX/LyMjcnDRpEkjjdisrK6wRraOj8wvokAMLFy788ejRoxcaGhrPCWai4ODgB8DUE3/mzBknYMToASNoMzAfvEVW4+Tk9LmhoWFbTU2NwunTpx2BjiiMjo6+hm4WCzJHUlLyz+vXrxkfP36sDOI/ffpUPikpibe7u3sLsJjQW7VqlSrQxe+Avjmanp7u9PbtWzGQOmCCkARmmu/m5uYfT548yY/V5UpKSl+2b9+uiiz26dMnIWBSDTp27NgdYGrYCIzwE7m5uR4wg2Hg/PnzSsDI/QlKOcjZ3wGUBLm5uf+DwLdv38gub4AG/xcSEvr35s0bZmCB5sgCE/z69SsjyDJKMtG/f/8YQQYD8wkoGf8H51AbG5sH1CpbQBnQ09PzBiiHgoIFFHlBQGwLxLxUMP8dqJgH4k3gIhfIkAKVYkDMTmmhCHIxEL8A4peMo02L4WU4QIABANxZAoDIQDv3AAAAAElFTkSuQmCC\" />
            <span class=\"sf-toolbar-status\">";
            // line 7
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["collector"]) ? $context["collector"] : null), "messageCount"), "html", null, true);
            echo "</span>
        ";
            $context["icon"] = ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
            // line 9
            echo "        ";
            ob_start();
            // line 10
            echo "            <div class=\"sf-toolbar-info-piece\">
                <b>Messages</b>
                <span>";
            // line 12
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["collector"]) ? $context["collector"] : null), "messageCount"), "html", null, true);
            echo "</span>
            </div>
            <div class=\"sf-toolbar-info-piece\">
                <b>Is spooled ?</b>
                <span>";
            // line 16
            echo (($this->getAttribute((isset($context["collector"]) ? $context["collector"] : null), "isSpool")) ? ("yes") : ("no"));
            echo "</span>
            </div>
        ";
            $context["text"] = ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
            // line 19
            echo "        ";
            $this->env->loadTemplate("WebProfilerBundle:Profiler:toolbar_item.html.twig")->display(array_merge($context, array("link" => (isset($context["profiler_url"]) ? $context["profiler_url"] : null))));
            // line 20
            echo "    ";
        }
    }

    // line 23
    public function block_menu($context, array $blocks = array())
    {
        // line 24
        echo "<span class=\"label\">
    <span class=\"icon\"><img src=\"";
        // line 25
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/webprofiler/images/profiler/mail.png"), "html", null, true);
        echo "\" alt=\"Configuration\" /></span>
    <strong>E-Mails</strong>
    <span class=\"count\">
        <span>";
        // line 28
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["collector"]) ? $context["collector"] : null), "messagecount"), "html", null, true);
        echo "</span>
    </span>
</span>
";
    }

    // line 33
    public function block_panel($context, array $blocks = array())
    {
        // line 34
        echo "    <h2>Messages ";
        echo (($this->getAttribute((isset($context["collector"]) ? $context["collector"] : null), "isSpool")) ? ("spooled") : ("sent"));
        echo "</h2>

    ";
        // line 36
        if ((!$this->getAttribute((isset($context["collector"]) ? $context["collector"] : null), "messages"))) {
            // line 37
            echo "        <p>
            <em>No message sent.</em>
        </p>
    ";
        } else {
            // line 41
            echo "        ";
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["collector"]) ? $context["collector"] : null), "messages"));
            foreach ($context['_seq'] as $context["i"] => $context["message"]) {
                // line 42
                echo "            <h3>Message ";
                echo twig_escape_filter($this->env, ((isset($context["i"]) ? $context["i"] : null) + 1), "html", null, true);
                echo " / ";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["collector"]) ? $context["collector"] : null), "messagecount"), "html", null, true);
                echo "</h3>

            ";
                // line 44
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute((isset($context["message"]) ? $context["message"] : null), "headers"), "all"));
                foreach ($context['_seq'] as $context["_key"] => $context["header"]) {
                    // line 45
                    echo "                <pre>";
                    echo twig_escape_filter($this->env, (isset($context["header"]) ? $context["header"] : null), "html", null, true);
                    echo "</pre>
            ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['header'], $context['_parent'], $context['loop']);
                $context = array_merge($_parent, array_intersect_key($context, $_parent));
                // line 47
                echo "
            <p>
                <pre>";
                // line 50
                if ($this->getAttribute((isset($context["message"]) ? $context["message"] : null), "charset")) {
                    // line 51
                    echo twig_escape_filter($this->env, twig_convert_encoding(twig_escape_filter($this->env, $this->getAttribute((isset($context["message"]) ? $context["message"] : null), "body"), "html", $this->getAttribute((isset($context["message"]) ? $context["message"] : null), "charset")), "UTF-8", $this->getAttribute((isset($context["message"]) ? $context["message"] : null), "charset")), "html", null, true);
                } else {
                    // line 53
                    echo twig_escape_filter($this->env, $this->getAttribute((isset($context["message"]) ? $context["message"] : null), "body"), "html");
                }
                // line 55
                echo "</pre>
            </p>
        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['i'], $context['message'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 58
            echo "    ";
        }
    }

    public function getTemplateName()
    {
        return "SwiftmailerBundle:Collector:swiftmailer.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  150 => 55,  144 => 51,  142 => 50,  129 => 45,  125 => 44,  117 => 42,  112 => 41,  106 => 37,  87 => 28,  70 => 20,  67 => 19,  61 => 16,  47 => 9,  91 => 20,  84 => 19,  74 => 16,  66 => 15,  55 => 13,  32 => 4,  28 => 3,  46 => 8,  225 => 96,  216 => 90,  212 => 88,  205 => 84,  201 => 83,  196 => 80,  194 => 79,  191 => 78,  189 => 77,  180 => 72,  172 => 67,  159 => 61,  154 => 59,  147 => 53,  121 => 45,  118 => 44,  114 => 43,  104 => 36,  100 => 34,  78 => 24,  75 => 23,  71 => 26,  63 => 24,  34 => 11,  202 => 3,  200 => 2,  186 => 76,  181 => 54,  176 => 55,  174 => 54,  168 => 52,  163 => 63,  158 => 58,  155 => 25,  139 => 22,  135 => 17,  132 => 48,  127 => 46,  109 => 52,  103 => 49,  90 => 46,  86 => 45,  65 => 35,  49 => 16,  37 => 12,  43 => 7,  29 => 3,  44 => 10,  25 => 4,  105 => 24,  96 => 21,  93 => 20,  83 => 18,  76 => 41,  72 => 14,  68 => 12,  58 => 22,  50 => 10,  41 => 9,  27 => 4,  24 => 3,  22 => 2,  19 => 1,  94 => 39,  88 => 6,  79 => 17,  59 => 22,  35 => 7,  31 => 5,  26 => 3,  21 => 2,  184 => 70,  178 => 71,  171 => 53,  165 => 58,  162 => 57,  157 => 60,  153 => 54,  151 => 53,  143 => 54,  138 => 47,  136 => 50,  133 => 43,  130 => 47,  122 => 37,  119 => 36,  116 => 60,  111 => 57,  108 => 31,  102 => 30,  98 => 34,  95 => 33,  92 => 27,  89 => 19,  85 => 24,  81 => 25,  73 => 19,  64 => 15,  60 => 23,  57 => 14,  54 => 12,  51 => 12,  48 => 19,  45 => 15,  42 => 7,  39 => 6,  36 => 5,  33 => 4,  30 => 3,);
    }
}
