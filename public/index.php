<?php

require_once(sprintf("%s/config/config.php", dirname($_SERVER['DOCUMENT_ROOT'])));
require_once("vendor/autoload.php");
require_once("app/controllers/LooperController.php");
require_once("app/controllers/QuizController.php");
require_once("app/controllers/QuestionController.php");

function main()
{
    $dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
        $r->addRoute('GET', '/', function() {
            $controller = new QuizController();
            $controller->index();
        });

        $r->addGroup('/quiz', function (FastRoute\RouteCollector $r) {
            $r->addRoute('GET', '/create', function() {
                $controller = new QuizController();
                $controller->create();
            });

            $r->addRoute('GET', '/{id:\d+}', function() {
                $controller = new QuizController();
                $controller->show();
            });
        });

        $r->addGroup('/question', function (FastRoute\RouteCollector $r) {
            $r->addRoute('GET', '/{id:\d+}/edit', function() {
                $controller = new QuestionController();
                $controller->edit();
            });
        });
    });

    // Fetch method and URI from somewhere
    $httpMethod = $_SERVER['REQUEST_METHOD'];
    $uri = $_SERVER['REQUEST_URI'];

    // Strip query string (?foo=bar) and decode URI
    if (false !== $pos = strpos($uri, '?')) {
        $uri = substr($uri, 0, $pos);
    }
    $uri = rawurldecode($uri);

    $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
    switch ($routeInfo[0]) {
        case FastRoute\Dispatcher::NOT_FOUND:
            // ... 404 Not Found
            break;
        case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
            $allowedMethods = $routeInfo[1];
            // ... 405 Method Not Allowed
            break;
        case FastRoute\Dispatcher::FOUND:
            $handler = $routeInfo[1];
            $vars = $routeInfo[2];

            $handler($vars);
            // ... call $handler with $vars
            break;
    }

}

main();