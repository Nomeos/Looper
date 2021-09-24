<?php

require_once("app/lib/Http/HttpRequest.php");

class Router
{
    private $dispatcher;

    public function __construct()
    {
        $this->setupDispatcher();
    }

    public function getDispatcher()
    {
        return $this->dispatcher;
    }

    private function setupDispatcher()
    {
        $this->dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
            $r->get('/', function () {
                $controller = new QuizController();
                $controller->index();
            });

            $this->setQuizRoutes($r);
            $this->setQuestionRoutes($r);
        });
    }

    private function setQuizRoutes(FastRoute\RouteCollector &$r)
    {
        $controller = new QuizController();
        $r->addGroup('/quiz', function (FastRoute\RouteCollector $r) use ($controller) {
            $r->get('/create', function() use ($controller) {
                $controller->create();
            });

            $r->post('/store', function() use ($controller) {
                // a HttpRequest instance is create within this closure
                // because when a HttpRequest object is instanciated,
                // its attributes come from the current http request
                $request = new HttpRequest();

                $controller->store($request);
            });

            $r->put('/{id:\d+}/update', function($args) use ($controller) {
                $request = new HttpRequest();

                $controller->update($request, $args["id"]);
            });

            $r->get('/{id:\d+}/edit', function($args) use ($controller) {
                $controller->edit($args["id"]);
            });
        });
    }

    private function setQuestionRoutes(FastRoute\RouteCollector &$r)
    {
        $controller = new QuestionController();

        $r->addGroup('/question', function (FastRoute\RouteCollector $r) use ($controller) {
            $r->get('/{id:\d+}/edit', function ($args) use ($controller) {
                $controller->edit($args);
            });

            $r->post('/create', function ($args) use ($controller) {
                $controller->store($args);
            });
        });
    }
}