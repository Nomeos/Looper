<?php

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
        $r->addGroup('/quiz', function (FastRoute\RouteCollector $r) {
            $r->get('/create', function() {
                $controller = new QuizController();
                $controller->create();
            });

            $r->post('/store', function() {
                $request = new HttpRequest();

                $controller = new QuizController();
                $controller->store($request);
            });

            $r->put('/{id:\d+}/update', function($args) {
                $request = new HttpRequest();

                $controller = new QuizController();
                $controller->update($request, $args["id"]);
            });

            $r->get('/{id:\d+}/edit', function($args) {
                $controller = new QuizController();
                $controller->edit($args["id"]);
            });
        });
    }

    private function setQuestionRoutes(FastRoute\RouteCollector &$r)
    {
        $r->addGroup('/question', function (FastRoute\RouteCollector $r) {
            $r->get('/{id:\d+}/edit', function ($args) {
                $controller = new QuestionController();
                $controller->edit($args);
            });

            $r->post('/create', function ($args) {
                $controller = new QuestionController();
                $controller->store($args);
            });
        });
    }
}