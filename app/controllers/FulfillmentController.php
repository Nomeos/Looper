<?php

namespace App\controllers;

use App\lib\CustomString;
use App\lib\FlashMessage;
use App\lib\http\CsrfToken;
use App\lib\http\Session;
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

        $csrf_token = CsrfToken::generate();
        $session = new Session();
        $session->set("csrf_token", $csrf_token);

        $data = [];
        $data["body"]["csrf_token"] = $csrf_token;

        $questions = Question::where("quiz_id", $quiz_id);
        $quiz = Quiz::find($quiz_id);

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
     * @param int $quiz_id , HttpRequest $request
     */
    public function store(HttpRequest $request, int $quiz_id)
    {
        $form_data = $request->getBodyData();
        $session = $request->getSession();

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

        $url = "/quiz/$quiz_id/fullfilment";
        if (!isset($form_data["csrf_token"]) || !hash_equals($form_data["csrf_token"], $session->get("csrf_token"))) {
            $_SESSION["flash_message"]["type"] = FlashMessage::ERROR;
            $_SESSION["flash_message"]["value"] = "Access denied!<br>";
            $_SESSION["flash_message"]["value"] .= "You token is either missing of was modified!";

            header("Location: $url");
            exit;
        }

        // remove token from form_data so it is easier to
        // loop through it and create answers
        if (isset($form_data["csrf_token"])) {
            unset($form_data["csrf_token"]);
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
            if (!is_int($question_id)) {
                return false;
            }
            $answer = Answer::make(["value" => CustomString::sanitize($response), "question_id" => CustomString::sanitize($question_id), "fulfillment_id" => $fulfillment->id]);
            if (!$answer->create()) {
                $connector->rollback();
                return false;
            }
        }
        $connector->commit();

        $url = "/fulfillment/{$fulfillment->id}/edit";
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
        $csrf_token = CsrfToken::generate();
        $session = new Session();
        $session->set("csrf_token", $csrf_token);

        $data = [];
        $data["body"]["csrf_token"] = $csrf_token;

        $fulfillment = Fulfillment::find($id);
        $questions = $fulfillment->questions();

        $data["body"]["fulfillment"] = $fulfillment;

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
        require_once("resources/views/fulfillment/update.php");
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
        $form_data = $request->getBodyData();
        $session = $request->getSession();

        $fulfillment = Fulfillment::find($id);
        if ($fulfillment === null) {
            $url = "/quiz/answering";
            $_SESSION["flash_message"]["type"] = FlashMessage::ERROR;
            $_SESSION["flash_message"]["value"] = "There is no fulfillment with id {$fulfillment->id}!";

            header("Location: $url");
            exit();
        }

        $url = "/fulfillment/{$fulfillment->id}/edit";
        if (!isset($form_data["csrf_token"]) || !hash_equals($form_data["csrf_token"], $session->get("csrf_token"))) {
            $_SESSION["flash_message"]["type"] = FlashMessage::ERROR;
            $_SESSION["flash_message"]["value"] = "Access denied!<br>";
            $_SESSION["flash_message"]["value"] .= "You token is either missing of was modified!";

            header("Location: $url");
            exit;
        }

        // update is a PUT request. we add an hidden input so we can do the request
        // in js. this removes the http method from the request body so we can
        // easily parse it and use each element as an answer.
        if (isset($form_data["_method"])) {
            unset($form_data["_method"]);
        }

        if (isset($form_data["csrf_token"])) {
            unset($form_data["csrf_token"]);
        }

        $connector = DB::getInstance();
        $connector->beginTransaction();

        // update answers
        $answer = null;
        foreach ($form_data as $answer_id => $response) {
            $answer = Answer::find($answer_id);

            // only update answers whose values were modified
            // reduces database requests
            if ($answer->value !== $response) {
                $answer->value = CustomString::sanitize($response);
                if (!$answer->save()) {
                    $connector->rollback();
                    $_SESSION["flash_message"]["type"] = FlashMessage::ERROR;
                    $_SESSION["flash_message"]["value"] = "Something went wrong while editing fulfillment!";

                    header("Location: $url");
                    exit();
                }
            }
        }
        $connector->commit();

        $_SESSION["flash_message"]["type"] = FlashMessage::OK;
        $_SESSION["flash_message"]["value"] = "Your answers were successfully updated!";

        header("Location: $url");
    }
}