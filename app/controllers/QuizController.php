<?php

namespace App\controllers;

use App\lib\FlashMessage;
use App\lib\http\HttpRequest;
use App\lib\ResourceController;
use App\models\Answer;
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
        $quiz_list = Quiz::all();
        $data = [];

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
        $data["header"]["title"] = "New question";

        // get body content
        ob_start();
        require_once("resources/views/templates/header.php");
        require_once("resources/views/answer/answer.php");
        $data["body"]["content"] = ob_get_clean();

        // finally, render page
        $this->view->render("templates/base.php", $data);
    }

    /**
     * @throws Exception
     */
    public function create()
    {
        $data = [];

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
        $quiz = new Quiz();
        $default_quiz_state = QuizState::where("label", "Building")[0];
        $form_data = $request->getBodyData();

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
        $quiz = Quiz::find($id);

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

        $data = [];

        // set title
        $data["head"]["title"] = "Edit {$quiz->title}";

        // load quiz into view
        $data["body"]["quiz"] = $quiz;

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
        // TODO: Implement destroy() method.
    }

    /**
     * @param int $id
     */
    public function results(int $id)
    {
        // TODO: Implement destroy() method.
    }

    public function admin()
    {
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
        $data["header"]["title"] = "admin view";

        // get body content
        ob_start();
        require_once("resources/views/templates/header.php");
        require_once("resources/views/admin/admin.php");
        $data["body"]["content"] = ob_get_clean();

        // finally, render page
        $this->view->render("templates/base.php", $data);
    }

    public function toAnswering()
    {
        echo "I'm in";
        exit;
    }

    public function toClosed()
    {

    }

    /**
     * @param int $id
     */
    public function fulfillment(int $id)
    {
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