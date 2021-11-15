<?php

namespace App\controllers;

use App\lib\FlashMessage;
use App\lib\ResourceController;
use App\lib\http\HttpRequest;
use App\models\Question;
use App\models\QuestionType;
use App\models\Quiz;
use App\models\QuizState;

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

    public function store(HttpRequest $request)
    {
        $request_data = $request->getBodyData();
        $quiz = null;

        $url = "/quiz/admin";
        if (!isset($request_data["quiz_id"])) {
            $_SESSION["flash_message"]["type"] = FlashMessage::ERROR;
            $_SESSION["flash_message"]["value"] = "Quiz id is missing!";

            header("Location: $url");
            exit;
        }

        $quiz = Quiz::find($request_data["quiz_id"]);
        if ($quiz === null) {
            $_SESSION["flash_message"]["type"] = FlashMessage::ERROR;
            $_SESSION["flash_message"]["value"] = "There is no quiz with id {$request_data["quiz_id"]}!";

            header("Location: $url");
            exit;
        }

        $question = Question::make([
            "label" => $request_data["question_label"],
            "question_type_id" => $request_data["question_type_id"],
            "quiz_id" => $request_data["quiz_id"],
        ]);

        $url = "/quiz/{$quiz->id}/edit";
        try {
            // do not check duplicate entry because there might be multiple questions with the same label
            $question->create();
        } catch (\PDOException $e) {
            $data["body"]["message"] = "Something went wrong while adding a new question to quiz => {$quiz->title}!";

            if ($e->getCode() === "23000") {
                $_SESSION["flash_message"]["type"] = FlashMessage::ERROR;
                $_SESSION["flash_message"]["value"] = "There already is a question named {$question->label} in quiz {$quiz->title}!";

                header("Location: $url");
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
            $this->view->render("templates/base.php", $data);
            exit();
        }

        $_SESSION["flash_message"]["type"] = FlashMessage::OK;
        $_SESSION["flash_message"]["value"] = "Question => {$question->label} was successfully added to {$quiz->title}!";

        header("Location: $url");
        exit;
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

    public function update(HttpRequest $request, int $id)
    {
        $url = "/question/$id/edit";
        $request_data = $request->getBodyData();

        $question = Question::find($id);
        if ($question === null) {
            $_SESSION["flash_message"]["type"] = FlashMessage::ERROR;
            $_SESSION["flash_message"]["value"] = "Question with id $id does not exist!";

            header("Location: $url");
        }

        if (isset($request_data["question_label"])) {
            $question->label = $request_data["question_label"];
        }

        if (isset($request_data["question_type_id"])) {
            $question_type = QuestionType::find($request_data["question_type_id"]);
            if ($question === null) {
                $_SESSION["flash_message"]["type"] = FlashMessage::ERROR;
                $_SESSION["flash_message"]["value"] = "Question type with id $id does not exist!";

                header("Location: $url");
                exit;
            }
            $question->question_type_id = $request_data["question_type_id"];
        }
        if (isset($request_data["quiz_id"])) {
            $question->quiz_id = $request_data["quiz_id"];
        }

        try {
            $question->save();
        } catch (\PDOException $e) {
            if ($e->getCode() === "23000") {
                $_SESSION["flash_message"]["type"] = FlashMessage::ERROR;
                $_SESSION["flash_message"]["value"] = "Failed to update question!<br>";
                $_SESSION["flash_message"]["value"] .= "There already is a question named {$question->label}!";

                header("Location: $url");
                exit;
            }
        }

        $_SESSION["flash_message"]["type"] = FlashMessage::OK;
        $_SESSION["flash_message"]["value"] = "Question was successfully updated!";

        header("Location: $url");
    }

    public function destroy(int $id)
    {
        // TODO: Implement destroy() method.
    }
}