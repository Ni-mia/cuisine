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

/* project/show.html.twig */
class __TwigTemplate_d36666e57657ceb55bd4e2562e6778df extends Template
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
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "project/show.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "project/show.html.twig"));

        $this->parent = $this->loadTemplate("base.html.twig", "project/show.html.twig", 3);
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

        yield "Show";
        
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
        yield "    <h1>";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, (isset($context["project"]) || array_key_exists("project", $context) ? $context["project"] : (function () { throw new RuntimeError('Variable "project" does not exist.', 8, $this->source); })()), "name", [], "any", false, false, false, 8), "html", null, true);
        yield "</h1>
    <p>";
        // line 9
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, (isset($context["project"]) || array_key_exists("project", $context) ? $context["project"] : (function () { throw new RuntimeError('Variable "project" does not exist.', 9, $this->source); })()), "description", [], "any", false, false, false, 9), "html", null, true);
        yield "</p>
    ";
        // line 10
        if ((CoreExtension::getAttribute($this->env, $this->source, (isset($context["project"]) || array_key_exists("project", $context) ? $context["project"] : (function () { throw new RuntimeError('Variable "project" does not exist.', 10, $this->source); })()), "deletedAt", [], "any", false, false, false, 10) == null)) {
            // line 11
            yield "    <li class=\"list-group-item\">
        <a href=\"";
            // line 12
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("project.edit", ["id" => CoreExtension::getAttribute($this->env, $this->source, (isset($context["project"]) || array_key_exists("project", $context) ? $context["project"] : (function () { throw new RuntimeError('Variable "project" does not exist.', 12, $this->source); })()), "id", [], "any", false, false, false, 12)]), "html", null, true);
            yield "\" class=\"btn btn-warning btn-sm\">Modifier</a>
        <form action=\"";
            // line 13
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("project.delete", ["id" => CoreExtension::getAttribute($this->env, $this->source, (isset($context["project"]) || array_key_exists("project", $context) ? $context["project"] : (function () { throw new RuntimeError('Variable "project" does not exist.', 13, $this->source); })()), "id", [], "any", false, false, false, 13)]), "html", null, true);
            yield "\" method=\"POST\" style=\"display: inline;\">
            <button type=\"submit\" class=\"btn btn-danger btn-sm\">Supprimer</button>
            <input type=\"hidden\" name=\"_token\" value=\"";
            // line 15
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderCsrfToken(("delete" . CoreExtension::getAttribute($this->env, $this->source, (isset($context["project"]) || array_key_exists("project", $context) ? $context["project"] : (function () { throw new RuntimeError('Variable "project" does not exist.', 15, $this->source); })()), "id", [], "any", false, false, false, 15))), "html", null, true);
            yield "\">
        </form>
    </li>
    ";
        }
        // line 19
        yield "
    <h2>Tâches liées au projet</h2>
    <ul class=\"list-group\">
        ";
        // line 22
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable((isset($context["tasks"]) || array_key_exists("tasks", $context) ? $context["tasks"] : (function () { throw new RuntimeError('Variable "tasks" does not exist.', 22, $this->source); })()));
        foreach ($context['_seq'] as $context["_key"] => $context["task"]) {
            // line 23
            yield "            <li class=\"list-group-item\">
                ";
            // line 24
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["task"], "title", [], "any", false, false, false, 24), "html", null, true);
            yield " (Status: ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["task"], "status", [], "any", false, false, false, 24), "value", [], "any", false, false, false, 24), "html", null, true);
            yield ") 
                <a href=\"";
            // line 25
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("task.show", ["id" => CoreExtension::getAttribute($this->env, $this->source, $context["task"], "id", [], "any", false, false, false, 25), "slug" => CoreExtension::getAttribute($this->env, $this->source, $context["task"], "slug", [], "any", false, false, false, 25)]), "html", null, true);
            yield "\" class=\"btn btn-info btn-sm\">Voir</a>
                <a href=\"";
            // line 26
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("task.edit", ["id" => CoreExtension::getAttribute($this->env, $this->source, $context["task"], "id", [], "any", false, false, false, 26)]), "html", null, true);
            yield "\" class=\"btn btn-warning btn-sm\">Modifier</a>
                <form action=\"";
            // line 27
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("task.delete", ["id" => CoreExtension::getAttribute($this->env, $this->source, $context["task"], "id", [], "any", false, false, false, 27)]), "html", null, true);
            yield "\" method=\"POST\" style=\"display: inline;\">
                    <button type=\"submit\" class=\"btn btn-danger btn-sm\">Supprimer</button>
                    <input type=\"hidden\" name=\"_token\" value=\"";
            // line 29
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderCsrfToken(("delete" . CoreExtension::getAttribute($this->env, $this->source, $context["task"], "id", [], "any", false, false, false, 29))), "html", null, true);
            yield "\">
                </form>
            </li>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['task'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 33
        yield "    </ul>
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
        return "project/show.html.twig";
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
        return array (  171 => 33,  161 => 29,  156 => 27,  152 => 26,  148 => 25,  142 => 24,  139 => 23,  135 => 22,  130 => 19,  123 => 15,  118 => 13,  114 => 12,  111 => 11,  109 => 10,  105 => 9,  100 => 8,  87 => 7,  64 => 5,  41 => 3,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{# templates/project/show.html.twig #}

{% extends 'base.html.twig' %}

{% block title %}Show{% endblock %}

{% block body %}
    <h1>{{ project.name }}</h1>
    <p>{{ project.description }}</p>
    {% if project.deletedAt == null %}
    <li class=\"list-group-item\">
        <a href=\"{{ path('project.edit', { 'id': project.id }) }}\" class=\"btn btn-warning btn-sm\">Modifier</a>
        <form action=\"{{ path('project.delete', { 'id': project.id }) }}\" method=\"POST\" style=\"display: inline;\">
            <button type=\"submit\" class=\"btn btn-danger btn-sm\">Supprimer</button>
            <input type=\"hidden\" name=\"_token\" value=\"{{ csrf_token('delete' ~ project.id) }}\">
        </form>
    </li>
    {% endif %}

    <h2>Tâches liées au projet</h2>
    <ul class=\"list-group\">
        {% for task in tasks %}
            <li class=\"list-group-item\">
                {{ task.title }} (Status: {{ task.status.value }}) 
                <a href=\"{{ path('task.show', { 'id': task.id, 'slug': task.slug }) }}\" class=\"btn btn-info btn-sm\">Voir</a>
                <a href=\"{{ path('task.edit', { 'id': task.id }) }}\" class=\"btn btn-warning btn-sm\">Modifier</a>
                <form action=\"{{ path('task.delete', { 'id': task.id }) }}\" method=\"POST\" style=\"display: inline;\">
                    <button type=\"submit\" class=\"btn btn-danger btn-sm\">Supprimer</button>
                    <input type=\"hidden\" name=\"_token\" value=\"{{ csrf_token('delete' ~ task.id) }}\">
                </form>
            </li>
        {% endfor %}
    </ul>
{% endblock %}
", "project/show.html.twig", "E:\\S5\\web\\final_web\\back\\templates\\project\\show.html.twig");
    }
}
