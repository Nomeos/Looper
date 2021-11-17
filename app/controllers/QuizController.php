<?php

namespace App\controllers;

use App\lib\FlashMessage;
use App\lib\http\CsrfToken;
use App\lib\http\HttpRequest;
use App\lib\http\Session;
use App\lib\ResourceController;
use App\models\Answer;
use App\models\QuestionType;
use App\models\Quiz;
use App\models\QuizState;
use Exception;

class QuizController extends ResourceController
{
    /**
     * @throws Exception
     */
    public function index()
    {
        $quiz_list = null;
        $data = [];

        $quiz_list = Quiz::all();
        // set title
        $data["head"]["title"] = "Looper";

        // load quiz list into view
        $data["body"]["quiz_list"]["answering"] = array_filter($quiz_list, function ($quiz) {
            return $quiz->state()->label === "Answering";
        });
        // set title
        $data["head"]["title"] = "Looper";

        // get css stylesheets
        ob_start();
        require_once("resources/views/answer/style.php");
        $data["head"]["css"] = ob_get_clean();

        // set header title (next to the logo)
        $data["header"]["title"] = "Take a quiz";

        // get body content
        ob_start();
        require_once("resources/views/templates/header.php");
        require_once("resources/views/fulfillment/list.php");
        $data["body"]["content"] = ob_get_clean();

        // finally, render page
        $this->view->render("templates/base.php", $data);
    }

    /**
     * @throws Exception
     */
    public function create()
    {
        $csrf_token = CsrfToken::generate();
        $session = new Session();
        $session->set("csrf_token", $csrf_token);

        $data = [];
        $data["body"]["csrf_token"] = $csrf_token;

        // set title
        $data["head"]["title"] = "Looper";

        // get css stylesheets
        ob_start();
        require_once("resources/views/quiz/style.php");
        $data["head"]["css"] = ob_get_clean();

        // set header title (next to the logo)
        $data["header"]["title"] = "New question";

        // get body content
        ob_start();
        require_once("resources/views/templates/header.php");
        require_once("resources/views/quiz/add.php");
        $data["body"]["content"] = ob_get_clean();

        // finally, render page
        $this->view->render("templates/base.php", $data);
    }

    /**
     * @param HttpRequest $request
     */
    public function store(HttpRequest $request)
    {
        $session = $request->getSession();

        $quiz = new Quiz();
        $default_quiz_state = QuizState::where("label", "Building")[0];
        $form_data = $request->getBodyData();

        $url = "/quiz/create";
        if (!isset($form_data["csrf_token"]) || !hash_equals($form_data["csrf_token"], $session->get("csrf_token"))) {
            $_SESSION["flash_message"]["type"] = FlashMessage::ERROR;
            $_SESSION["flash_message"]["value"] = "Access denied!<br>";
            $_SESSION["flash_message"]["value"] .= "You token is either missing of was modified!";

            header("Location: $url");
            exit;
        }

        $quiz->title = $form_data["quiz_title"];
        $quiz->is_public = false;
        $quiz->quiz_state_id = $default_quiz_state->id;

        $url = "/quiz/create";
        try {
            $quiz->create();
            $_SESSION["flash_message"]["type"] = FlashMessage::OK;
            $_SESSION["flash_message"]["value"] = "Quiz was successfully created!";

            header("Location: $url");
        } catch (\PDOException $e) {
            if ($e->getCode() === "23000") {
                $_SESSION["flash_message"]["type"] = FlashMessage::ERROR;
                $_SESSION["flash_message"]["value"] = "Failed to create a new quiz!<br>";
                $_SESSION["flash_message"]["value"] .= "There already is a quiz named {$quiz->title}!";

                header("Location: $url");
            }
        }
    }

    /**
     * @param int $id
     */
    public function show(int $id)
    {
        // TODO: Implement store() method.
    }

    /**
     * @param int $id
     * @throws Exception
     */
    public function edit(int $id)
    {
        $quiz = null;
        $question_types = null;

        $csrf_token = CsrfToken::generate();
        $session = new Session();
        $session->set("csrf_token", $csrf_token);

        $quiz = Quiz::find($id);
        $question_types = QuestionType::all();

        $data = [];
        $data["body"]["csrf_token"] = $csrf_token;

        // If there is no quiz with 'id', show proper error message
        if ($quiz === null) {
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
        $data["head"]["title"] = "Edit {$quiz->title}";

        // load quiz into view
        $data["body"]["quiz"] = $quiz;
        $data["body"]["question_types"] = $question_types;

        // get css stylesheets
        ob_start();
        require_once("resources/views/quiz/style.php");
        require_once("resources/views/question/style.php");
        $data["head"]["css"] = ob_get_clean();

        // set header title (next to the logo)
        $data["header"]["title"] = $quiz->title;

        ob_start();
        require_once("resources/views/question/list.php");
        $data["body"]["questions_list"] = ob_get_clean();

        ob_start();
        require_once("resources/views/question/add.php");
        $data["body"]["questions_add"] = ob_get_clean();

        // get body content
        ob_start();
        require_once("resources/views/templates/header.php");
        require_once("resources/views/quiz/update.php");
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
        $quiz = null;
        $data = [];
        $url = "/quiz/admin";
        $quiz = Quiz::find($id);

        if ($quiz === null) {
            $_SESSION["flash_message"]["type"] = FlashMessage::ERROR;
            $_SESSION["flash_message"]["value"] = "Quiz wasn't found!";

            header("Location: $url");
        }

        $quiz->delete();

        $_SESSION["flash_message"]["type"] = FlashMessage::OK;
        $_SESSION["flash_message"]["value"] = "Quiz was successfully deleted!";

        header("Location: $url");
    }

    public function admin()
    {
        $quiz_list = [];
        $data = [];

        $quiz_list = Quiz::all();

        // set title
        $data["head"]["title"] = "Looper";

        // load quiz list into view
        $data["body"]["quiz_list"]["building"] = array_filter($quiz_list, function ($quiz) {
            return $quiz->state()->label === "Building";
        });
        $data["body"]["quiz_list"]["answering"] = array_filter($quiz_list, function ($quiz) {
            return $quiz->state()->label === "Answering";
        });
        $data["body"]["quiz_list"]["closed"] = array_filter($quiz_list, function ($quiz) {
            return $quiz->state()->label === "Closed";
        });

        // get css stylesheets
        ob_start();
        require_once("resources/views/admin/style.php");
        $data["head"]["css"] = ob_get_clean();

        // set header title (next to the logo)
        $data["header"]["title"] = "Admin panel";

        // get body content
        ob_start();
        require_once("resources/views/templates/header.php");
        require_once("resources/views/admin/admin.php");
        $data["body"]["content"] = ob_get_clean();

        // finally, render page
        $this->view->render("templates/base.php", $data);
    }

    public function toAnswering(int $id)
    {
        $quiz = null;
        $data = [];

        $url = "/quiz/admin";
        $quiz = Quiz::find($id);

        if ($quiz === null) {

            $_SESSION["flash_message"]["type"] = FlashMessage::ERROR;
            $_SESSION["flash_message"]["value"] = "Quiz wasn't found!";

            header("Location: $url");
            exit;
        }

        $nextState = QuizState::where("label", "Answering");
        if ($nextState[0] !== null) {
            $quiz->quiz_state_id = $nextState[0]->id;
        } else {
            $_SESSION["flash_message"]["type"] = FlashMessage::ERROR;
            $_SESSION["flash_message"]["value"] = "Quiz state wasn't found!";

            header("Location: $url");
            exit;
        }
        $quiz->save();

        $_SESSION["flash_message"]["type"] = FlashMessage::OK;
        $_SESSION["flash_message"]["value"] = "Quiz was successfully changed to Answering!";

        header("Location: $url");
    }

    public function toClosed(int $id)
    {
        $quiz = null;
        $data = [];
        $url = "/quiz/admin";

        $quiz = Quiz::find($id);

        if ($quiz === null) {

            $_SESSION["flash_message"]["type"] = FlashMessage::ERROR;
            $_SESSION["flash_message"]["value"] = "Quiz wasn't found!";

            header("Location: $url");
            exit;
        }

        $nextState = QuizState::where("label", "Closed");
        if ($nextState[0] !== null) {
            $quiz->quiz_state_id = $nextState[0]->id;
        } else {
            $_SESSION["flash_message"]["type"] = FlashMessage::ERROR;
            $_SESSION["flash_message"]["value"] = "Quiz state wasn't found!";

            header("Location: $url");
            exit;
        }
        $quiz->save();

        $_SESSION["flash_message"]["type"] = FlashMessage::OK;
        $_SESSION["flash_message"]["value"] = "Quiz was successfully closed";

        header("Location: $url");
    }

    /**
     * @param int $id
     */
    public function results(int $id)
    {
        $quiz = null;
        $quizFulfillments = null;
        $data = [];

        $quiz = Quiz::find($id);
        $quizFulfillments = $quiz->fulfillments($quiz->id);

        // set title
        $data["head"]["title"] = "Results";

        // get css stylesheets
        ob_start();
        require_once("resources/views/admin/style.php");
        $data["head"]["css"] = ob_get_clean();

        // set header title (next to the logo)
        $data["header"]["title"] = "Exercice: {$quiz->title}";

        // get body content
        ob_start();
        require_once("resources/views/templates/header.php");
        require_once("resources/views/admin/results.php");
        $data["body"]["content"] = ob_get_clean();

        // finally, render page
        $this->view->render("templates/base.php", $data);
    }
}