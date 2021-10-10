<?php

namespace App\lib;
use Exception;
use FastRoute\RouteCollector;

use App\controllers\QuizController;
use App\controllers\QuestionController;
use App\lib\http\HttpRequest;

class RouteFactory
{
    /**
     * @throws Exception
     */
    public static function parseControllerName(string $controllerName): string
    {
        $matches = [];
        $regex = '/[a-zA-Z]*(?=Controller)/';

        if (preg_match($regex, $controllerName, $matches) === false) {
            throw new Exception("Failed to parse regex!");
        }

        if (count($matches) != 1) {
            throw new Exception("Invalid controller name!");
        }

        return strtolower($matches[0]);
    }

    /**
     * @throws Exception
     */
    public static function fromResourceController(string $controllerName, RouteCollector &$r)
    {
        // without specifying the full namespace, composer
        // will not be able to load the class
        $controllerName = "App\\controllers\\$controllerName";
        $controller = new $controllerName();
        $controllerRouteName = self::parseControllerName($controllerName);

        $r->addGroup("/$controllerRouteName", function (RouteCollector $r) use ($controller) {
            // route => domain.com/quiz
            $r->get("", function () use ($controller) {
                $controller->index();
            });

            $r->get("/create", function () use ($controller) {
                $controller->create();
            });

            $r->post("/store", function () use ($controller) {
                // a HttpRequest instance is create within this closure
                // because when a HttpRequest object is instantiated,
                // its attributes come from the current http request
                $request = new HttpRequest();

                $controller->store($request);
            });

            $r->get("/{id:\d+}/edit", function ($args) use ($controller) {
                $controller->edit($args["id"]);
            });

            $r->get("/{id:\d+}", function ($args) use ($controller) {
                $controller->show($args["id"]);
            });

            $r->put("/{id:\d+}", function ($args) use ($controller) {
                $request = new HttpRequest();

                $controller->update($request, $args["id"]);
            });

            $r->delete("/{id:\d+}", function ($args) use ($controller) {
                $controller->destroy($args["id"]);
            });
        });
    }
}
