<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/_profiler' => [[['_route' => '_profiler_home', '_controller' => 'web_profiler.controller.profiler::homeAction'], null, null, null, true, false, null]],
        '/_profiler/search' => [[['_route' => '_profiler_search', '_controller' => 'web_profiler.controller.profiler::searchAction'], null, null, null, false, false, null]],
        '/_profiler/search_bar' => [[['_route' => '_profiler_search_bar', '_controller' => 'web_profiler.controller.profiler::searchBarAction'], null, null, null, false, false, null]],
        '/_profiler/phpinfo' => [[['_route' => '_profiler_phpinfo', '_controller' => 'web_profiler.controller.profiler::phpinfoAction'], null, null, null, false, false, null]],
        '/_profiler/xdebug' => [[['_route' => '_profiler_xdebug', '_controller' => 'web_profiler.controller.profiler::xdebugAction'], null, null, null, false, false, null]],
        '/_profiler/open' => [[['_route' => '_profiler_open_file', '_controller' => 'web_profiler.controller.profiler::openAction'], null, null, null, false, false, null]],
        '/api/ingredients' => [[['_route' => 'app_api_ingredientsapi_list', '_controller' => 'App\\Controller\\API\\IngredientsApiController::list'], null, ['GET' => 0], null, false, false, null]],
        '/api/ingredient' => [[['_route' => 'app_api_ingredientsapi_create', '_controller' => 'App\\Controller\\API\\IngredientsApiController::create'], null, ['POST' => 0], null, false, false, null]],
        '/api/liaisonPlatIngredients' => [
            [['_route' => 'app_api_liaisonplatingredientsapi_list', '_controller' => 'App\\Controller\\API\\LiaisonPlatIngredientsApiController::list'], null, ['GET' => 0], null, false, false, null],
            [['_route' => 'app_api_liaisonplatingredientsapi_create', '_controller' => 'App\\Controller\\API\\LiaisonPlatIngredientsApiController::create'], null, ['POST' => 0], null, false, false, null],
        ],
        '/api/plats' => [[['_route' => 'app_api_platapi_list', '_controller' => 'App\\Controller\\API\\PlatApiController::list'], null, ['GET' => 0], null, false, false, null]],
        '/api/plat' => [[['_route' => 'app_api_platapi_create', '_controller' => 'App\\Controller\\API\\PlatApiController::create'], null, ['POST' => 0], null, false, false, null]],
        '/api/projects' => [[['_route' => 'app_api_projectapi_list', '_controller' => 'App\\Controller\\API\\ProjectApiController::list'], null, ['GET' => 0], null, false, false, null]],
        '/api/project' => [[['_route' => 'app_api_projectapi_create', '_controller' => 'App\\Controller\\API\\ProjectApiController::create'], null, ['POST' => 0], null, false, false, null]],
        '/api/tasks' => [
            [['_route' => 'app_api_taskapi_create', '_controller' => 'App\\Controller\\API\\TaskApiController::create'], null, ['POST' => 0], null, false, false, null],
            [['_route' => 'app_api_taskapi_findall', '_controller' => 'App\\Controller\\API\\TaskApiController::findAll'], null, ['GET' => 0], null, false, false, null],
        ],
        '/api/users' => [
            [['_route' => 'app_api_userapi_create', '_controller' => 'App\\Controller\\API\\UserApiController::create'], null, ['POST' => 0], null, false, false, null],
            [['_route' => 'app_api_userapi_findall', '_controller' => 'App\\Controller\\API\\UserApiController::findAll'], null, ['GET' => 0], null, false, false, null],
        ],
        '/category' => [[['_route' => 'app_category_index', '_controller' => 'App\\Controller\\CategoryController::index'], null, ['GET' => 0], null, false, false, null]],
        '/category/new' => [[['_route' => 'app_category_new', '_controller' => 'App\\Controller\\CategoryController::new'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
        '/' => [[['_route' => 'home', '_controller' => 'App\\Controller\\HomeController::index'], null, null, null, false, false, null]],
        '/project' => [[['_route' => 'project.index', '_controller' => 'App\\Controller\\ProjectController::index'], null, ['GET' => 0], null, false, false, null]],
        '/project/new' => [[['_route' => 'project.new', '_controller' => 'App\\Controller\\ProjectController::new'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
        '/register' => [[['_route' => 'app_register', '_controller' => 'App\\Controller\\RegistrationController::register'], null, null, null, false, false, null]],
        '/login' => [[['_route' => 'app_login', '_controller' => 'App\\Controller\\SecurityController::login'], null, null, null, false, false, null]],
        '/logout' => [[['_route' => 'app_logout', '_controller' => 'App\\Controller\\SecurityController::logout'], null, null, null, false, false, null]],
        '/tasks' => [[['_route' => 'task.index', '_controller' => 'App\\Controller\\TaskController::index'], null, null, null, false, false, null]],
        '/tasks/create' => [[['_route' => 'task.create', '_controller' => 'App\\Controller\\TaskController::create'], null, null, null, false, false, null]],
        '/tasks/paginate' => [[['_route' => 'task.paginate', '_controller' => 'App\\Controller\\TaskController::paginate'], null, null, null, false, false, null]],
        '/user' => [[['_route' => 'app_user_index', '_controller' => 'App\\Controller\\UserController::index'], null, ['GET' => 0], null, false, false, null]],
        '/user/new' => [[['_route' => 'app_user_new', '_controller' => 'App\\Controller\\UserController::new'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/_(?'
                    .'|error/(\\d+)(?:\\.([^/]++))?(*:38)'
                    .'|wdt/([^/]++)(*:57)'
                    .'|profiler/(?'
                        .'|font/([^/\\.]++)\\.woff2(*:98)'
                        .'|([^/]++)(?'
                            .'|/(?'
                                .'|search/results(*:134)'
                                .'|router(*:148)'
                                .'|exception(?'
                                    .'|(*:168)'
                                    .'|\\.css(*:181)'
                                .')'
                            .')'
                            .'|(*:191)'
                        .')'
                    .')'
                .')'
                .'|/api/(?'
                    .'|ingredient/([^/]++)(?'
                        .'|(*:232)'
                    .')'
                    .'|liaisonPlatIngredients/(?'
                        .'|([^/]++)(?'
                            .'|(*:278)'
                        .')'
                        .'|makeRecette(*:298)'
                    .')'
                    .'|p(?'
                        .'|lat(?'
                            .'|/([^/]++)(?'
                                .'|(*:329)'
                            .')'
                            .'|s/([^/]++)/ingredients(*:360)'
                        .')'
                        .'|roject(?'
                            .'|/([^/]++)(?'
                                .'|(*:390)'
                            .')'
                            .'|s/([^/]++)(*:409)'
                        .')'
                    .')'
                    .'|tasks/(?'
                        .'|([0-9]+)(*:436)'
                        .'|([^/]++)(?'
                            .'|(*:455)'
                        .')'
                    .')'
                    .'|users/(?'
                        .'|([0-9]+)(*:482)'
                        .'|([^/]++)(*:498)'
                        .'|login(*:511)'
                    .')'
                .')'
                .'|/category/([^/]++)(?'
                    .'|(*:542)'
                    .'|/edit(*:555)'
                    .'|(*:563)'
                .')'
                .'|/project/([^/]++)(?'
                    .'|(*:592)'
                    .'|/edit(*:605)'
                    .'|(*:613)'
                .')'
                .'|/task(?'
                    .'|s/(?'
                        .'|([a-z0-9-]+)\\-(\\d+)(*:654)'
                        .'|([^/]++)/edit(*:675)'
                    .')'
                    .'|/([^/]++)/delete(*:700)'
                .')'
                .'|/user/([^/]++)(?'
                    .'|(*:726)'
                    .'|/edit(*:739)'
                    .'|(*:747)'
                .')'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        38 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        57 => [[['_route' => '_wdt', '_controller' => 'web_profiler.controller.profiler::toolbarAction'], ['token'], null, null, false, true, null]],
        98 => [[['_route' => '_profiler_font', '_controller' => 'web_profiler.controller.profiler::fontAction'], ['fontName'], null, null, false, false, null]],
        134 => [[['_route' => '_profiler_search_results', '_controller' => 'web_profiler.controller.profiler::searchResultsAction'], ['token'], null, null, false, false, null]],
        148 => [[['_route' => '_profiler_router', '_controller' => 'web_profiler.controller.router::panelAction'], ['token'], null, null, false, false, null]],
        168 => [[['_route' => '_profiler_exception', '_controller' => 'web_profiler.controller.exception_panel::body'], ['token'], null, null, false, false, null]],
        181 => [[['_route' => '_profiler_exception_css', '_controller' => 'web_profiler.controller.exception_panel::stylesheet'], ['token'], null, null, false, false, null]],
        191 => [[['_route' => '_profiler', '_controller' => 'web_profiler.controller.profiler::panelAction'], ['token'], null, null, false, true, null]],
        232 => [
            [['_route' => 'app_api_ingredientsapi_detail', '_controller' => 'App\\Controller\\API\\IngredientsApiController::detail'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => 'app_api_ingredientsapi_edit', '_controller' => 'App\\Controller\\API\\IngredientsApiController::edit'], ['id'], ['PUT' => 0], null, false, true, null],
            [['_route' => 'app_api_ingredientsapi_delete', '_controller' => 'App\\Controller\\API\\IngredientsApiController::delete'], ['id'], ['DELETE' => 0], null, false, true, null],
        ],
        278 => [
            [['_route' => 'app_api_liaisonplatingredientsapi_detail', '_controller' => 'App\\Controller\\API\\LiaisonPlatIngredientsApiController::detail'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => 'app_api_liaisonplatingredientsapi_edit', '_controller' => 'App\\Controller\\API\\LiaisonPlatIngredientsApiController::edit'], ['id'], ['PUT' => 0], null, false, true, null],
            [['_route' => 'app_api_liaisonplatingredientsapi_delete', '_controller' => 'App\\Controller\\API\\LiaisonPlatIngredientsApiController::delete'], ['id'], ['DELETE' => 0], null, false, true, null],
        ],
        298 => [[['_route' => 'app_api_liaisonplatingredientsapi_makerecette', '_controller' => 'App\\Controller\\API\\LiaisonPlatIngredientsApiController::makeRecette'], [], ['POST' => 0], null, false, false, null]],
        329 => [
            [['_route' => 'app_api_platapi_detail', '_controller' => 'App\\Controller\\API\\PlatApiController::detail'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => 'app_api_platapi_edit', '_controller' => 'App\\Controller\\API\\PlatApiController::edit'], ['id'], ['PUT' => 0], null, false, true, null],
            [['_route' => 'app_api_platapi_delete', '_controller' => 'App\\Controller\\API\\PlatApiController::delete'], ['id'], ['DELETE' => 0], null, false, true, null],
        ],
        360 => [[['_route' => 'app_api_platapi_getingredientsbyplat', '_controller' => 'App\\Controller\\API\\PlatApiController::getIngredientsByPlat'], ['id'], ['GET' => 0], null, false, false, null]],
        390 => [
            [['_route' => 'app_api_projectapi_detail', '_controller' => 'App\\Controller\\API\\ProjectApiController::detail'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => 'app_api_projectapi_edit', '_controller' => 'App\\Controller\\API\\ProjectApiController::edit'], ['id'], ['PUT' => 0], null, false, true, null],
            [['_route' => 'app_api_projectapi_delete', '_controller' => 'App\\Controller\\API\\ProjectApiController::delete'], ['id'], ['DELETE' => 0], null, false, true, null],
        ],
        409 => [[['_route' => 'app_api_userapi_delete', '_controller' => 'App\\Controller\\API\\UserApiController::delete'], ['id'], ['DELETE' => 0], null, false, true, null]],
        436 => [[['_route' => 'app_api_taskapi_findbyid', '_controller' => 'App\\Controller\\API\\TaskApiController::findById'], ['id'], ['GET' => 0], null, false, true, null]],
        455 => [
            [['_route' => 'app_api_taskapi_update', '_controller' => 'App\\Controller\\API\\TaskApiController::update'], ['id'], ['PUT' => 0], null, false, true, null],
            [['_route' => 'app_api_taskapi_delete', '_controller' => 'App\\Controller\\API\\TaskApiController::delete'], ['id'], ['DELETE' => 0], null, false, true, null],
        ],
        482 => [[['_route' => 'app_api_userapi_findbyid', '_controller' => 'App\\Controller\\API\\UserApiController::findById'], ['id'], ['GET' => 0], null, false, true, null]],
        498 => [[['_route' => 'app_api_userapi_update', '_controller' => 'App\\Controller\\API\\UserApiController::update'], ['id'], ['PUT' => 0], null, false, true, null]],
        511 => [[['_route' => 'app_api_userapi_login', '_controller' => 'App\\Controller\\API\\UserApiController::login'], [], ['POST' => 0], null, false, false, null]],
        542 => [[['_route' => 'app_category_show', '_controller' => 'App\\Controller\\CategoryController::show'], ['id'], ['GET' => 0], null, false, true, null]],
        555 => [[['_route' => 'app_category_edit', '_controller' => 'App\\Controller\\CategoryController::edit'], ['id'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        563 => [[['_route' => 'app_category_delete', '_controller' => 'App\\Controller\\CategoryController::delete'], ['id'], ['POST' => 0], null, false, true, null]],
        592 => [[['_route' => 'project.show', '_controller' => 'App\\Controller\\ProjectController::show'], ['id'], ['GET' => 0], null, false, true, null]],
        605 => [[['_route' => 'project.edit', '_controller' => 'App\\Controller\\ProjectController::edit'], ['id'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        613 => [[['_route' => 'project.delete', '_controller' => 'App\\Controller\\ProjectController::delete'], ['id'], ['POST' => 0], null, false, true, null]],
        654 => [[['_route' => 'task.show', '_controller' => 'App\\Controller\\TaskController::show'], ['slug', 'id'], null, null, false, true, null]],
        675 => [[['_route' => 'task.edit', '_controller' => 'App\\Controller\\TaskController::edit'], ['id'], null, null, false, false, null]],
        700 => [[['_route' => 'task.delete', '_controller' => 'App\\Controller\\TaskController::delete'], ['id'], ['POST' => 0], null, false, false, null]],
        726 => [[['_route' => 'app_user_show', '_controller' => 'App\\Controller\\UserController::show'], ['id'], ['GET' => 0], null, false, true, null]],
        739 => [[['_route' => 'app_user_edit', '_controller' => 'App\\Controller\\UserController::edit'], ['id'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        747 => [
            [['_route' => 'app_user_delete', '_controller' => 'App\\Controller\\UserController::delete'], ['id'], ['POST' => 0], null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
