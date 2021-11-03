<?php

namespace App\controllers;

use App\lib\ResourceController;
use App\lib\http\HttpRequest;
use App\models\Answer;
use App\models\Question;

class AnswerController extends ResourceController
{
    /**
     * @throws Exception
     */
    public function index()
    {
        $data = [];

        // set title
        $data["head"]["title"] = "Results";

        // get css stylesheets
        ob_start();
        require_once("resources/views/admin/style.php");
        $data["head"]["css"] = ob_get_clean();

        // set header title (next to the logo)
        $data["header"]["title"] = "Exercice: lapin ou lapin";

        // get body content
        ob_start();
        require_once("resources/views/templates/header.php");
        require_once("resources/views/admin/results.php");
        $data["body"]["content"] = ob_get_clean();

        // finally, render page
        $this->view->render("templates/base.php", $data);
    }

    /**
     * @throws Exception
     */
    public function create()
    {

    }

    /**
     * @param HttpRequest $request
     */
    public function store(HttpRequest $request)
    {
        // TODO: Implement store() method.
        echo "<pre>";
        print_r($request->getBodyData());
        echo "</pre>";
    }

    /**
     * @param int $id
     */
    public function show(int $id)
    {
        $data = [];

        // set title
        $data["head"]["title"] = "Your answer";

        // get css stylesheets
        ob_start();
        require_once("resources/views/admin/style.php");
        $data["head"]["css"] = ob_get_clean();

        // set header title (next to the logo)
        $data["header"]["title"] = "Exercise: lapin ou lapin";

        // get body content
        ob_start();
        require_once("resources/views/templates/header.php");
        require_once("resources/views/admin/show.php");
        $data["body"]["content"] = ob_get_clean();

        // finally, render page
        $this->view->render("templates/base.php", $data);
    }

    /**
     * @param int $id
     * @throws Exception
     */
    public function edit(int $id)
    {
        $questions = Question::where("quiz_id", $id);
        $data = [];

        // set title
        $data["head"]["title"] = "Edit your answer";

        // get css stylesheets
        ob_start();
        require_once("resources/views/fulfillment/style.php");
        $data["head"]["css"] = ob_get_clean();

        // set header title (next to the logo)
        $data["header"]["title"] = "YOUR ANSWER";

        // get body content
        ob_start();
        require_once("resources/views/templates/header.php");
        require_once("resources/views/fulfillment/fulfillment.php");
        $data["body"]["content"] = ob_get_clean();

        // finally, render page
        $this->view->render("templates/base.php", $data);
    }

    /**
     * @param HttpRequest $request
     * @param int $id
     */
    public function update(HttpRequest $request, int $id)
    {

        // TODO: Implement update() method.
    }

    /**
     * @param int $id
     */
    public function destroy(int $id)
    {
        // TODO: Implement destroy() method.
    }

    /**
     * @param int $id
     */
    public function results(int $id)
    {
        // TODO: Implement destroy() method.
    }
}