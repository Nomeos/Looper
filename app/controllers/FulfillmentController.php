<?php

namespace App\controllers;

use App\lib\FlashMessage;
use App\lib\ResourceController;
use App\lib\http\HttpRequest;
use App\lib\View;
use App\models\Answer;
use App\models\Fulfillment;
use App\models\Question;
use App\models\QuestionType;
use App\models\Quiz;
use DateTimeZone;
use Thynkon\SimpleOrm\database\DB;

class FulfillmentController
{
    private View $view;

    public function __construct()
    {
        $this->view = new View();
    }

    /**
     * @throws Exception
     */
    public function create(int $quiz_id)
    {
        $questions = null;
        $quiz = null;
        $data = [];

        try {
            $questions = Question::where("quiz_id", $quiz_id);
            $quiz = Quiz::find($quiz_id);
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

        $data["body"]["quiz"] = $quiz;

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
        require_once("resources/views/fulfillment/create.php");
        $data["body"]["content"] = ob_get_clean();

        // finally, render page
        $this->view->render("templates/base.php", $data);
    }

    /**
     * @param int $quiz_id, HttpRequest $request
     */
    public function store(int $quiz_id, HttpRequest $request)
    {
        $form_data = $request->getBodyData();
        $date = new \DateTime();
        $timezone = new DateTimeZone("UTC");
        $date->setTimezone($timezone);

        $fulfillment = Fulfillment::make(["date" => $date->format("Y-m-d H:i:s")]);

        $quiz = Quiz::find($quiz_id);
        if ($quiz === null) {
            $url = "/quiz/answering";
            $_SESSION["flash_message"]["type"] = FlashMessage::ERROR;
            $_SESSION["flash_message"]["value"] = "There is no quiz with id {$quiz->id}!";

            header("Location: $url");
            exit();
        }

        $connector = DB::getInstance();

        $connector->beginTransaction();
        if (!$fulfillment->create()) {
            $connector->rollback();
            return false;
        }

        // add answers
        $answer = null;
        foreach ($form_data as $question_id => $response) {
            $answer = Answer::make(["value" => $response, "question_id" => $question_id, "fulfillment_id" => $fulfillment->id]);
            if (!$answer->create()) {
                $connector->rollback();
                return false;
            }
        }
        $connector->commit();

        $url = "/quiz/answering";
        $_SESSION["flash_message"]["type"] = FlashMessage::OK;
        $_SESSION["flash_message"]["value"] = "Your answers were successfully saved!";

        header("Location: $url");
    }

    /**
     * @param int $id
     */
    public function show(int $id)
    {
        $fulfillment = Fulfillment::find($id);
        $quiz = $fulfillment->quiz($id);
        $answers = $fulfillment->answers($id);
        $data = [];
        $data["body"]["answers"] = $answers;
        $data["body"]["fulfillment"] = $fulfillment;

        // set title
        $data["head"]["title"] = "Looper";

        // get css stylesheets
        ob_start();
        require_once("resources/views/admin/style.php");
        $data["head"]["css"] = ob_get_clean();

        // set header title (next to the logo)
        $data["header"]["title"] = "Exercise: {$quiz->title}";

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
        $questions = null;
        $data = [];

        try {
            $questions = Question::where("quiz_id", $id);
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
        require_once("resources/views/fulfillment/create.php");
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


}