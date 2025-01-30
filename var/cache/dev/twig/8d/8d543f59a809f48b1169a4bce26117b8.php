<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;
use Twig\TemplateWrapper;

/* task/index.html.twig */
class __TwigTemplate_c35c55e3118b52f86657a50699388ca7 extends Template
{
    private Source $source;
    /**
     * @var array<string, Template>
     */
    private array $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'title' => [$this, 'block_title'],
            'body' => [$this, 'block_body'],
        ];
    }

    protected function doGetParent(array $context): bool|string|Template|TemplateWrapper
    {
        // line 1
        return "base.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "task/index.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "task/index.html.twig"));

        $this->parent = $this->loadTemplate("base.html.twig", "task/index.html.twig", 1);
        yield from $this->parent->unwrap()->yield($context, array_merge($this->blocks, $blocks));
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 3
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_title(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "title"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "title"));

        yield "Les taches";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        yield from [];
    }

    // line 5
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_body(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "body"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "body"));

        // line 6
        yield "<div class=\"container\">
    <form method=\"get\" class=\"mb-4\">
        <div class=\"row\">
            ";
        // line 10
        yield "            <div class=\"col-md-4\">
                <input type=\"text\" name=\"search\" class=\"form-control\" placeholder=\"Recherche par titre\" value=\"";
        // line 11
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((isset($context["searchTitle"]) || array_key_exists("searchTitle", $context) ? $context["searchTitle"] : (function () { throw new RuntimeError('Variable "searchTitle" does not exist.', 11, $this->source); })()), "html", null, true);
        yield "\">
            </div>

            ";
        // line 15
        yield "            <div class=\"col-md-3\">
                <input type=\"number\" name=\"min_estimate\" class=\"form-control\" placeholder=\"Estimation Min\" value=\"";
        // line 16
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((isset($context["minEstimate"]) || array_key_exists("minEstimate", $context) ? $context["minEstimate"] : (function () { throw new RuntimeError('Variable "minEstimate" does not exist.', 16, $this->source); })()), "html", null, true);
        yield "\">
            </div>

            ";
        // line 20
        yield "            <div class=\"col-md-3\">
                <input type=\"number\" name=\"max_estimate\" class=\"form-control\" placeholder=\"Estimation Max\" value=\"";
        // line 21
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((isset($context["maxEstimate"]) || array_key_exists("maxEstimate", $context) ? $context["maxEstimate"] : (function () { throw new RuntimeError('Variable "maxEstimate" does not exist.', 21, $this->source); })()), "html", null, true);
        yield "\">
            </div>

            ";
        // line 25
        yield "            <div class=\"col-md-2\">
                <button type=\"submit\" class=\"btn btn-primary w-100\">Filtrer</button>
            </div>
        </div>
    </form>

    <div class=\"d-flex justify-content-between my-2\">
        <a href=\"";
        // line 32
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("task.create");
        yield "\" class=\"btn btn-primary\">Créer une tache</a>
        <p>Total estimation : ";
        // line 33
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((isset($context["totalEstimates"]) || array_key_exists("totalEstimates", $context) ? $context["totalEstimates"] : (function () { throw new RuntimeError('Variable "totalEstimates" does not exist.', 33, $this->source); })()), "html", null, true);
        yield "</p>
    </div>


    <ul class=\"list-group\">
        ";
        // line 38
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable((isset($context["tasks"]) || array_key_exists("tasks", $context) ? $context["tasks"] : (function () { throw new RuntimeError('Variable "tasks" does not exist.', 38, $this->source); })()));
        foreach ($context['_seq'] as $context["_key"] => $context["task"]) {
            // line 39
            yield "            <a class=\"list-group-item list-group-item-action ";
            if ((CoreExtension::getAttribute($this->env, $this->source, $context["task"], "deletedAt", [], "any", false, false, false, 39) != null)) {
                yield " bg-secondary";
            }
            yield " \" href=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getUrl("task.show", ["id" => CoreExtension::getAttribute($this->env, $this->source, $context["task"], "id", [], "any", false, false, false, 39), "slug" => CoreExtension::getAttribute($this->env, $this->source, $context["task"], "slug", [], "any", false, false, false, 39)]), "html", null, true);
            yield "\">";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["task"], "title", [], "any", false, false, false, 39), "html", null, true);
            yield " (Estimation : ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["task"], "estimates", [], "any", false, false, false, 39), "html", null, true);
            yield ")</a>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['task'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 41
        yield "    </ul>

    ";
        // line 51
        yield "
    ";
        // line 52
        yield $this->env->getRuntime('Knp\Bundle\PaginatorBundle\Twig\Extension\PaginationRuntime')->render($this->env, (isset($context["tasks"]) || array_key_exists("tasks", $context) ? $context["tasks"] : (function () { throw new RuntimeError('Variable "tasks" does not exist.', 52, $this->source); })()));
        yield "

</div>
";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "task/index.html.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable(): bool
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo(): array
    {
        return array (  181 => 52,  178 => 51,  174 => 41,  157 => 39,  153 => 38,  145 => 33,  141 => 32,  132 => 25,  126 => 21,  123 => 20,  117 => 16,  114 => 15,  108 => 11,  105 => 10,  100 => 6,  87 => 5,  64 => 3,  41 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% extends 'base.html.twig' %}

{% block title \"Les taches\" %}

{% block body %}
<div class=\"container\">
    <form method=\"get\" class=\"mb-4\">
        <div class=\"row\">
            {# Barre de recherche par titre #}
            <div class=\"col-md-4\">
                <input type=\"text\" name=\"search\" class=\"form-control\" placeholder=\"Recherche par titre\" value=\"{{ searchTitle }}\">
            </div>

            {# Filtre Min #}
            <div class=\"col-md-3\">
                <input type=\"number\" name=\"min_estimate\" class=\"form-control\" placeholder=\"Estimation Min\" value=\"{{ minEstimate }}\">
            </div>

            {# Filtre Max #}
            <div class=\"col-md-3\">
                <input type=\"number\" name=\"max_estimate\" class=\"form-control\" placeholder=\"Estimation Max\" value=\"{{ maxEstimate }}\">
            </div>

            {# Bouton de filtrage #}
            <div class=\"col-md-2\">
                <button type=\"submit\" class=\"btn btn-primary w-100\">Filtrer</button>
            </div>
        </div>
    </form>

    <div class=\"d-flex justify-content-between my-2\">
        <a href=\"{{ path('task.create') }}\" class=\"btn btn-primary\">Créer une tache</a>
        <p>Total estimation : {{ totalEstimates }}</p>
    </div>


    <ul class=\"list-group\">
        {% for task in tasks %}
            <a class=\"list-group-item list-group-item-action {% if task.deletedAt != null %} bg-secondary{% endif %} \" href=\"{{ url('task.show', {id: task.id, slug: task.slug}) }}\">{{task.title}} (Estimation : {{task.estimates}})</a>
        {% endfor %}
    </ul>

    {# <div class=\"d-flex\">
        {% if page > 1 %}
            <a href=\"{{path('task.index', {page: page - 1}) }}\" class=\"btn btn-secondary\">Page précedent</a>
        {% endif %}
          {% if page < maxPage %}
            <a href=\"{{path('task.index', {page: page + 1}) }}\" class=\"btn btn-secondary\">Page suivante</a>
        {% endif %}
    </div> #}

    {{ knp_pagination_render(tasks) }}

</div>
{% endblock %}
", "task/index.html.twig", "E:\\S5\\web\\final_web\\back\\templates\\task\\index.html.twig");
    }
}
