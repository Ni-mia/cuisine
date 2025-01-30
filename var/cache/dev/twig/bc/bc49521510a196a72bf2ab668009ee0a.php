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

/* includes/header.html.twig */
class __TwigTemplate_623803f7b908280c7fbde445dad70a55 extends Template
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

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "includes/header.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "includes/header.html.twig"));

        // line 1
        yield "<nav class=\"navbar navbar-expand-lg bg-body-tertiary\">
    <div class=\"container-fluid\">
        <a class=\"navbar-brand\" href=\"";
        // line 3
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getUrl("home");
        yield "\">CRUD</span>
        <button class=\"navbar-toggler\" type=\"button\" data-bs-toggle=\"collapse\" data-bs-target=\"#navbarNav\" aria-controls=\"navbarNav\" aria-expanded=\"false\" aria-label=\"Toggle navigation\">
            <span class=\"navbar-toggler-icon\"></span>
        </button>
        <div class=\"collapse navbar-collapse\">
            <ul class=\"navbar-nav\">
                <li class=\"nav-item\">
                    <a></a>
                </li>
                <li class=\"nav-item\">
                    <a class=\"nav-link ";
        // line 13
        yield (((CoreExtension::getAttribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new RuntimeError('Variable "app" does not exist.', 13, $this->source); })()), "current_route", [], "any", false, false, false, 13) == "home")) ? ("active") : (""));
        yield "\" href=\"";
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getUrl("home");
        yield "\">Accueil</a>
                </li>
                <li class=\"nav-item\">
                    <a class=\"nav-link ";
        // line 16
        yield (((is_string($__internal_compile_0 = CoreExtension::getAttribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new RuntimeError('Variable "app" does not exist.', 16, $this->source); })()), "current_route", [], "any", false, false, false, 16)) && is_string($__internal_compile_1 = "project") && str_starts_with($__internal_compile_0, $__internal_compile_1))) ? ("active") : (""));
        yield "\" href=\"";
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getUrl("project.index");
        yield "\">Projects</a>
                </li>
                <li class=\"nav-item\">
                    <a class=\"nav-link ";
        // line 19
        yield (((is_string($__internal_compile_2 = CoreExtension::getAttribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new RuntimeError('Variable "app" does not exist.', 19, $this->source); })()), "current_route", [], "any", false, false, false, 19)) && is_string($__internal_compile_3 = "task") && str_starts_with($__internal_compile_2, $__internal_compile_3))) ? ("active") : (""));
        yield "\" href=\"";
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getUrl("task.index");
        yield "\">Taches</a>
                </li>
                <li class=\"nav-item\">
                    <a class=\"nav-link ";
        // line 22
        yield (((is_string($__internal_compile_4 = CoreExtension::getAttribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new RuntimeError('Variable "app" does not exist.', 22, $this->source); })()), "current_route", [], "any", false, false, false, 22)) && is_string($__internal_compile_5 = "user") && str_starts_with($__internal_compile_4, $__internal_compile_5))) ? ("active") : (""));
        yield "\" href=\"";
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getUrl("app_user_index");
        yield "\">User</a>
                </li>
                <li class=\"nav-item\">
                    <a class=\"nav-link ";
        // line 25
        yield (((is_string($__internal_compile_6 = CoreExtension::getAttribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new RuntimeError('Variable "app" does not exist.', 25, $this->source); })()), "current_route", [], "any", false, false, false, 25)) && is_string($__internal_compile_7 = "category") && str_starts_with($__internal_compile_6, $__internal_compile_7))) ? ("active") : (""));
        yield "\" href=\"";
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getUrl("app_category_index");
        yield "\">Category</a>
                </li>
            </ul>
        </div>
    </div>
</nav>";
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "includes/header.html.twig";
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
        return array (  97 => 25,  89 => 22,  81 => 19,  73 => 16,  65 => 13,  52 => 3,  48 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("<nav class=\"navbar navbar-expand-lg bg-body-tertiary\">
    <div class=\"container-fluid\">
        <a class=\"navbar-brand\" href=\"{{ url('home') }}\">CRUD</span>
        <button class=\"navbar-toggler\" type=\"button\" data-bs-toggle=\"collapse\" data-bs-target=\"#navbarNav\" aria-controls=\"navbarNav\" aria-expanded=\"false\" aria-label=\"Toggle navigation\">
            <span class=\"navbar-toggler-icon\"></span>
        </button>
        <div class=\"collapse navbar-collapse\">
            <ul class=\"navbar-nav\">
                <li class=\"nav-item\">
                    <a></a>
                </li>
                <li class=\"nav-item\">
                    <a class=\"nav-link {{ app.current_route == 'home' ? 'active' : '' }}\" href=\"{{ url('home') }}\">Accueil</a>
                </li>
                <li class=\"nav-item\">
                    <a class=\"nav-link {{ app.current_route starts with 'project' ? 'active' : '' }}\" href=\"{{ url('project.index') }}\">Projects</a>
                </li>
                <li class=\"nav-item\">
                    <a class=\"nav-link {{ app.current_route starts with 'task' ? 'active' : '' }}\" href=\"{{ url('task.index') }}\">Taches</a>
                </li>
                <li class=\"nav-item\">
                    <a class=\"nav-link {{ app.current_route starts with 'user' ? 'active' : '' }}\" href=\"{{ url('app_user_index') }}\">User</a>
                </li>
                <li class=\"nav-item\">
                    <a class=\"nav-link {{ app.current_route starts with 'category' ? 'active' : '' }}\" href=\"{{ url('app_category_index') }}\">Category</a>
                </li>
            </ul>
        </div>
    </div>
</nav>", "includes/header.html.twig", "E:\\S5\\web\\final_web\\back\\templates\\includes\\header.html.twig");
    }
}
