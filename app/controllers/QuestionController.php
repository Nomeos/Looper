<?php

namespace App\controllers;

use App\lib\CustomString;
use App\lib\FlashMessage;
use App\lib\http\CsrfToken;
use App\lib\http\Session;
use App\lib\ResourceController;
use App\lib\http\HttpRequest;
use App\models\Question;
use App\models\QuestionType;
use App\models\Quiz;
use App\models\QuizState;
use function PHPUnit\Framework\throwException;

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
        $form_data = $request->getBodyData();
        $session = $request->getSession();

        $quiz = null;

        $url = "/quiz/admin";
        if (!isset($form_data["quiz_id"])) {
            $_SESSION["flash_message"]["type"] = FlashMessage::ERROR;
            $_SESSION["flash_message"]["value"] = "Quiz id is missing!";

            header("Location: $url");
            exit;
        }

        $url = "/quiz/{$form_data["quiz_id"]}/edit";
        if (!isset($form_data["csrf_token"]) || !hash_equals($form_data["csrf_token"], $session->get("csrf_token"))) {
            $_SESSION["flash_message"]["type"] = FlashMessage::ERROR;
            $_SESSION["flash_message"]["value"] = "Access denied!<br>";
            $_SESSION["flash_message"]["value"] .= "You token is either missing of was modified!";

            header("Location: $url");
            exit;
        }

        $quiz = Quiz::find($form_data["quiz_id"]);
        if ($quiz === null) {
            $_SESSION["flash_message"]["type"] = FlashMessage::ERROR;
            $_SESSION["flash_message"]["value"] = "There is no quiz with id {$form_data["quiz_id"]}!";

            header("Location: $url");
            exit;
        }

        $question = Question::make([
            "label" => CustomString::sanitize($form_data["question_label"]),
            "question_type_id" => CustomString::sanitize($form_data["question_type_id"]),
            "quiz_id" => CustomString::sanitize($form_data["quiz_id"]),
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
                exit;
            }

            // let index.php to show generic error message
            throwException($e);
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

        $csrf_token = CsrfToken::generate();
        $session = new Session();
        $session->set("csrf_token", $csrf_token);

        $data = [];
        $data["body"]["csrf_token"] = $csrf_token;

        $question = Question::find($id);
        $question_types = QuestionType::all();

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
        $form_data = $request->getBodyData();
        $session = $request->getSession();

        $question = Question::find($id);
        if ($question === null) {
            $_SESSION["flash_message"]["type"] = FlashMessage::ERROR;
            $_SESSION["flash_message"]["value"] = "Question with id $id does not exist!";

            header("Location: $url");
        }

        if (!isset($form_data["csrf_token"]) || !hash_equals($form_data["csrf_token"], $session->get("csrf_token"))) {
            $_SESSION["flash_message"]["type"] = FlashMessage::ERROR;
            $_SESSION["flash_message"]["value"] = "Access denied!<br>";
            $_SESSION["flash_message"]["value"] .= "You token is either missing of was modified!";

            header("Location: $url");
            exit;
        }

        if (isset($form_data["question_label"])) {
            $question->label = CustomString::sanitize($form_data["question_label"]);
        }

        if (isset($form_data["question_type_id"])) {
            $question_type = QuestionType::find($form_data["question_type_id"]);
            if ($question === null) {
                $_SESSION["flash_message"]["type"] = FlashMessage::ERROR;
                $_SESSION["flash_message"]["value"] = "Question type with id $id does not exist!";

                header("Location: $url");
                exit;
            }
            $question->question_type_id = $form_data["question_type_id"];
        }
        if (isset($form_data["quiz_id"])) {
            $question->quiz_id = $form_data["quiz_id"];
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
        $question = null;
        $url = null;
        $quiz_id = null;

        $question = Question::find($id);
        $url = "/quiz/admin";

        // If there is no question with 'id', show proper error message
        if ($question === null) {
            $_SESSION["flash_message"]["type"] = FlashMessage::ERROR;
            $_SESSION["flash_message"]["value"] = "There is not question with id $id!";

            header("Location: $url");
            exit;
        }

        $quiz_id = $question->quiz_id;
        $url = "/quiz/$quiz_id/edit";

        if (!$question->delete()) {
            $_SESSION["flash_message"]["type"] = FlashMessage::ERROR;
            $_SESSION["flash_message"]["value"] = "Something went wrong while deleting question {$question->label}!";

            header("Location: $url");
            exit;
        }

        $_SESSION["flash_message"]["type"] = FlashMessage::OK;
        $_SESSION["flash_message"]["value"] = "Question was successfully deleted!";

        header("Location: $url");
    }
}