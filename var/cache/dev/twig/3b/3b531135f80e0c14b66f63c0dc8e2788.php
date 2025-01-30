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

/* project/index.html.twig */
class __TwigTemplate_a1095f7697563eb38e644fdeac1e37ff extends Template
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
        // line 3
        return "base.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "project/index.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "project/index.html.twig"));

        $this->parent = $this->loadTemplate("base.html.twig", "project/index.html.twig", 3);
        yield from $this->parent->unwrap()->yield($context, array_merge($this->blocks, $blocks));
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 5
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

        yield "Les projects";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        yield from [];
    }

    // line 7
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

        // line 8
        yield "<div class=\"container\">
    <form method=\"get\" class=\"mb-4\">
        <div class=\"row\">
            ";
        // line 12
        yield "            <div class=\"col-md-4\">
                <input type=\"text\" name=\"search\" class=\"form-control\" placeholder=\"Recherche par nom\" value=\"";
        // line 13
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((isset($context["searchTitle"]) || array_key_exists("searchTitle", $context) ? $context["searchTitle"] : (function () { throw new RuntimeError('Variable "searchTitle" does not exist.', 13, $this->source); })()), "html", null, true);
        yield "\">
            </div>
            ";
        // line 16
        yield "            <div class=\"col-md-2\">
                <button type=\"submit\" class=\"btn btn-primary w-100\">Filtrer</button>
            </div>
        </div>
    </form>

    <div class=\"d-flex justify-content-between my-2\">
        <a href=\"";
        // line 23
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("project.new");
        yield "\" class=\"btn btn-primary\">Créer une project</a>
    </div>


    <ul class=\"list-group\">
        ";
        // line 28
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable((isset($context["projects"]) || array_key_exists("projects", $context) ? $context["projects"] : (function () { throw new RuntimeError('Variable "projects" does not exist.', 28, $this->source); })()));
        foreach ($context['_seq'] as $context["_key"] => $context["project"]) {
            // line 29
            yield "            <a class=\"list-group-item list-group-item-action ";
            if ((CoreExtension::getAttribute($this->env, $this->source, $context["project"], "deletedAt", [], "any", false, false, false, 29) != null)) {
                yield " bg-secondary";
            }
            yield " \" href=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getUrl("project.show", ["id" => CoreExtension::getAttribute($this->env, $this->source, $context["project"], "id", [], "any", false, false, false, 29)]), "html", null, true);
            yield "\">";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["project"], "name", [], "any", false, false, false, 29), "html", null, true);
            yield "</a>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['project'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 31
        yield "    </ul>

    ";
        // line 41
        yield "
    ";
        // line 42
        yield $this->env->getRuntime('Knp\Bundle\PaginatorBundle\Twig\Extension\PaginationRuntime')->render($this->env, (isset($context["projects"]) || array_key_exists("projects", $context) ? $context["projects"] : (function () { throw new RuntimeError('Variable "projects" does not exist.', 42, $this->source); })()));
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
        return "project/index.html.twig";
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
        return array (  156 => 42,  153 => 41,  149 => 31,  134 => 29,  130 => 28,  122 => 23,  113 => 16,  108 => 13,  105 => 12,  100 => 8,  87 => 7,  64 => 5,  41 => 3,);
    }

    public function getSourceContext(): Source
    {
        return new Source("

{% extends 'base.html.twig' %}

{% block title \"Les projects\" %}

{% block body %}
<div class=\"container\">
    <form method=\"get\" class=\"mb-4\">
        <div class=\"row\">
            {# Barre de recherche par titre #}
            <div class=\"col-md-4\">
                <input type=\"text\" name=\"search\" class=\"form-control\" placeholder=\"Recherche par nom\" value=\"{{ searchTitle }}\">
            </div>
            {# Bouton de filtrage #}
            <div class=\"col-md-2\">
                <button type=\"submit\" class=\"btn btn-primary w-100\">Filtrer</button>
            </div>
        </div>
    </form>

    <div class=\"d-flex justify-content-between my-2\">
        <a href=\"{{ path('project.new') }}\" class=\"btn btn-primary\">Créer une project</a>
    </div>


    <ul class=\"list-group\">
        {% for project in projects %}
            <a class=\"list-group-item list-group-item-action {% if project.deletedAt != null %} bg-secondary{% endif %} \" href=\"{{ url('project.show', {id: project.id}) }}\">{{project.name}}</a>
        {% endfor %}
    </ul>

    {# <div class=\"d-flex\">
        {% if page > 1 %}
            <a href=\"{{path('project.index', {page: page - 1}) }}\" class=\"btn btn-secondary\">Page précedent</a>
        {% endif %}
          {% if page < maxPage %}
            <a href=\"{{path('project.index', {page: page + 1}) }}\" class=\"btn btn-secondary\">Page suivante</a>
        {% endif %}
    </div> #}

    {{ knp_pagination_render(projects) }}

</div>
{% endblock %}
", "project/index.html.twig", "E:\\S5\\web\\final_web\\back\\templates\\project\\index.html.twig");
    }
}
