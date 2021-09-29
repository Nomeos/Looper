<?php

require_once("app/lib/RouteFactory.php");
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
            // set index route
            $r->get('/', function () {
                $controller = new LooperController();
                $controller->index();
            });

            // create CRUD routes for both controllers (like Laravel)
            RouteFactory::fromResourceController('QuizController', $r);
            RouteFactory::fromResourceController('QuestionController', $r);

            // append extra routes (that are not part of CRUD actions)
            $this->setQuizRoutes($r);
            $this->setQuestionRoutes($r);
        });
    }

    private function setQuizRoutes(FastRoute\RouteCollector &$r)
    {
        $controller = new QuizController();
        $r->addGroup('/quiz', function (FastRoute\RouteCollector $r) use ($controller) {
            $r->get('/{id:\d+}/results', function($args) use ($controller) {
                $controller->results($args["id"]);
            });
        });
    }

    private function setQuestionRoutes(FastRoute\RouteCollector &$r)
    {
        // DO NOTHING FOR NOW
    }
}