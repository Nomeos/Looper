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
            FlashMessage::error("Quiz id is missing!");

            header("Location: $url");
            exit;
        }

        $url = "/quiz/{$form_data["quiz_id"]}/edit";
        if (!isset($form_data["csrf_token"]) || !hash_equals($form_data["csrf_token"], $session->get("csrf_token"))) {
            $message = "Access denied!<br>";
            $message .= "You token is either missing of was modified!";
            FlashMessage::error($message);

            header("Location: $url");
            exit;
        }

        $quiz = Quiz::find($form_data["quiz_id"]);
        if ($quiz === null) {
            FlashMessage::error("There is no quiz with id {$form_data["quiz_id"]}!");

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
                FlashMessage::error("There already is a question named {$question->label} in quiz {$quiz->title}!");

                header("Location: $url");
                exit;
            } else if ($e->getCode() === "22001") {
                FlashMessage::error(sprintf("Your question's length is bigger than %d !", Question::MAX_LABEL_LENGTH));

                header("Location: $url");
                exit;
            }

            // let index.php to show generic error message for other exceptions
            throw $e;
        }

        FlashMessage::success("Question => {$question->label} was successfully added to {$quiz->title}!");

        header("Location: $url");
        exit;
    }

    public function show(int $id)
    {
        $question = null;
        $question = Question::find($id);
        $csrf_token = CsrfToken::generate();
        $session = new Session();
        $session->set("csrf_token", $csrf_token);

        $answers = $question->answers();

        $data = [];
        $data["body"]["csrf_token"] = $csrf_token;
        $data["body"]["answers"] = $answers;

        // If there is no quiz with 'id', show proper error message
        if ($question === null) {
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
            $this->view->render("templates/base.php", $data);
            exit;
        }

        // set title
        $data["head"]["title"] = "Show {$question->label}";

        // load quiz into view
        $data["body"]["question"] = $question;

        // get css stylesheets
        ob_start();
        require_once("resources/views/quiz/style.php");
        require_once("resources/views/question/style.php");
        $data["head"]["css"] = ob_get_clean();

        // set header title (next to the logo)
        $data["header"]["title"] = $question->label;

        // get body content
        ob_start();
        require_once("resources/views/templates/header.php");
        require_once("resources/views/quiz/show.php");
        $data["body"]["content"] = ob_get_clean();

        // finally, render page
        $this->view->render("templates/base.php", $data);
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

        $quiz = $question->quiz();
        if (!$quiz->isEditable()) {
            $url = "/quiz/admin";

            $message = "Access denied!<br>";
            $message .= "You are trying to modify a question whose quiz is not editable anymore!";
            FlashMessage::error($message);

            header("Location: $url");
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
            FlashMessage::error("Question with id $id does not exist!");

            header("Location: $url");
            exit;
        }

        $quiz = $question->quiz();
        if (!$quiz->isEditable()) {
            $url = "/quiz/admin";

            $message = "Access denied!<br>";
            $message .= "You are trying to modify a question whose quiz is not editable anymore!";
            FlashMessage::error($message);

            header("Location: $url");
            exit;
        }

        if (!isset($form_data["csrf_token"]) || !hash_equals($form_data["csrf_token"], $session->get("csrf_token"))) {
            $message = "Access denied!<br>";
            $message .= "You token is either missing of was modified!";
            FlashMessage::error($message);

            header("Location: $url");
            exit;
        }

        if (isset($form_data["question_label"])) {
            $question->label = CustomString::sanitize($form_data["question_label"]);
        }

        if (isset($form_data["question_type_id"])) {
            $question_type = QuestionType::find($form_data["question_type_id"]);
            if ($question === null) {
                FlashMessage::error("Question type with id $id does not exist!");

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
                $message = "Failed to update question!<br>";
                $message .= "There already is a question named {$question->label}!";
                FlashMessage::error($message);

                header("Location: $url");
                exit;
            }
        }

        FlashMessage::success("Question was successfully updated!");

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
            FlashMessage::error("There is not question with id $id!");

            header("Location: $url");
            exit;
        }

        $quiz_id = $question->quiz_id;
        $url = "/quiz/$quiz_id/edit";

        if (!$question->delete()) {
            FlashMessage::error("Something went wrong while deleting question {$question->label}!");

            header("Location: $url");
            exit;
        }

        FlashMessage::success("Question was successfully deleted!");
        header("Location: $url");
    }
}