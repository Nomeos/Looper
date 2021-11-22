<?php

require_once(sprintf("%s/config/config.php", dirname($_SERVER['DOCUMENT_ROOT'])));
require_once("vendor/autoload.php");

use App\controllers\LooperController;
use App\controllers\QuizController;
use App\controllers\QuestionController;
use App\lib\http\Session;
use App\lib\View;
use Route\Router;

function main()
{
    $session = new Session();
    $view = new View();
    $data = [];
    // Fetch routes
    $router = new Router();
    $dispatcher = $router->getDispatcher();

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
            http_response_code(404);

            // get css stylesheets
            ob_start();
            require_once("resources/views/error/style.php");
            $data["head"]["css"] = ob_get_clean();

            // set header title (tab title)
            $data["head"]["title"] = "Page not found";

            ob_start();
            require_once("resources/views/error/404.php");
            $data["body"]["content"] = ob_get_clean();

            // finally, render page
            $view->render("templates/base.php", $data);
            break;
        case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
            $allowedMethods = $routeInfo[1];
            // ... 405 Method Not Allowed
            break;
        case FastRoute\Dispatcher::FOUND:
            $handler = $routeInfo[1];
            $vars = $routeInfo[2];

            try {
                // call handler function (load template)
                $handler($vars);
                break;
            } catch (PDOException $e) {
                if ($e->getCode() === 2002) {
                    $data["body"]["message"] = "Database connection error!";
                }
                // get css stylesheets
                ob_start();
                require_once("resources/views/error/style.php");
                $data["head"]["css"] = ob_get_clean();

                // set header title (tab title)
                $data["head"]["title"] = "Internal error";

                ob_start();
                require_once("resources/views/error/500.php");
                $data["body"]["content"] = ob_get_clean();

                // finally, render page
                $view->render("templates/base.php", $data);
                exit;
            }
    }
}

main();