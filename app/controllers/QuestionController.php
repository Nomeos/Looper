<?php

namespace App\controllers;

use App\lib\ResourceController;
use App\lib\http\HttpRequest;
use App\models\Question;
use App\models\QuestionType;

class QuestionController extends ResourceController
{

    public function index()
    {
        // TODO: Implement index() method.
    }

    public function create()
    {
        // TODO: Implement create() method.
    }

    public function store($request)
    {
        // TODO: Implement store() method.
    }

    public function show(int $id)
    {
        // TODO: Implement show() method.
    }

    public function edit(int $id)
    {
        $question = null;
        $question_types = null;
        $data = [];

        try {
            $question = Question::find($id);
            $question_types = QuestionType::all();
        } catch (\PDOException $e) {
            $data["body"]["message"] = "Database connection error!<br>";
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
            $this->view->render("templates/base.php", $data);
        }

        // If there is no question with 'id', show proper error message
        if ($question === null) {
            header("HTTP/1.0 404 Not Found");
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
            $this->view->render("templates/base.php", $data);
            exit;
        }

        $data = [];

        // set title
        $data["head"]["title"] = "Edit {$question->label}";

        // load question into view
        $data["body"]["question"] = $question;
        $data["body"]["question_types"] = $question_types;

        // get css stylesheets
        ob_start();
        require_once("resources/views/question/style.php");
        $data["head"]["css"] = ob_get_clean();

        // set header title (next to the logo)
        $data["header"]["title"] = $question->label;

        // get body content
        ob_start();
        require_once("resources/views/templates/header.php");
        require_once("resources/views/question/update.php");
        $data["body"]["content"] = ob_get_clean();

        // finally, render page
        $this->view->render("templates/base.php", $data);
    }

    public function update($request, $id)
    {
        // TODO: Implement update() method.
    }

    public function destroy(int $id)
    {
        // TODO: Implement destroy() method.
    }
}