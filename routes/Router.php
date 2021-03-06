<?php

namespace Route;

use App\controllers\FulfillmentController;
use App\controllers\LooperController;
use App\controllers\QuizController;
use App\lib\RouteFactory;
use App\lib\http\HttpRequest;
use App\models\Answer;
use FastRoute\Dispatcher;
use FastRoute;

class Router
{
    private Dispatcher $dispatcher;

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
            RouteFactory::fromResourceController('FulfillmentController', $r);

            // append extra routes (that are not part of CRUD actions)
            $this->setQuizRoutes($r);
            $this->setQuestionRoutes($r);
            $this->setAnswerRoutes($r);
        });
    }

    private function setQuizRoutes(FastRoute\RouteCollector &$r)
    {
        $controller = new QuizController();
        $r->addGroup('/quiz', function (FastRoute\RouteCollector $r) use ($controller) {
            $r->get('/answering', function () use ($controller) {
                $controller->index();
            });
            $r->get('/admin', function () use ($controller) {
                $controller->admin();
            });

            $r->put("/{id:\d+}/toAnswering", function ($args) use ($controller) {
                $controller->toAnswering($args["id"]);
            });

            $r->put("/{id:\d+}/toClosed", function ($args) use ($controller) {
                $controller->toClosed($args["id"]);
            });

            $r->get("/{id:\d+}/results", function ($args) use ($controller) {
                $controller->results($args["id"]);
            });

            $r->get("/{id:\d+}/fullfilment", function ($args) {
                $controller = new FulfillmentController();
                $controller->create($args["id"]);
            });

            $r->post("/{id:\d+}/fullfilment", function ($args) {
                // a HttpRequest instance is create within this closure
                // because when a HttpRequest object is instantiated,
                // its attributes come from the current http request
                $request = new HttpRequest();

                $controller = new FulfillmentController();
                $controller->store($request, $args["id"]);
            });
        });
    }

    private function setQuestionRoutes(FastRoute\RouteCollector &$r)
    {
        // DO NOTHING FOR NOW
    }

    private function setAnswerRoutes(FastRoute\RouteCollector &$r)
    {
// DO NOTHING FOR NOW
    }

}