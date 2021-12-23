<?php

namespace App\controllers;

use App\lib\CustomString;
use App\lib\FlashMessage;
use App\lib\http\CsrfToken;
use App\lib\http\HttpRequest;
use App\lib\http\Session;
use App\lib\ResourceController;
use App\models\Answer;
use App\models\Question;
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
        $data = [];

        // set title
        $data["head"]["title"] = "Looper";

        // load quiz list into view
        $data["body"]["quiz_list"]["answering"] = Quiz::answeringList();
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
        $default_quiz_state = QuizState::defaultState();
        $form_data = $request->getBodyData();

        $url = "/quiz/create";
        if (!isset($form_data["csrf_token"]) || !hash_equals($form_data["csrf_token"], $session->get("csrf_token"))) {
            $message = "Access denied!<br>";
            $message .= "You token is either missing of was modified!";
            FlashMessage::error($message);

            header("Location: $url");
            exit;
        }

        $quiz->title = CustomString::sanitize($form_data["quiz_title"]);
        $quiz->is_public = false;
        $quiz->quiz_state_id = $default_quiz_state->id;

        $url = "/quiz/create";
        try {
            $quiz->create();
            FlashMessage::success("Quiz was successfully created!");

            header("Location: $url");
            exit();
        } catch (\PDOException $e) {
            if ($e->getCode() === "23000") {
                $message = "Failed to create a new quiz!<br>";
                $message .= "There already is a quiz named {$quiz->title}!";
                FlashMessage::error($message);

                header("Location: $url");
                exit();
            } else if ($e->getCode() === "22001") {
                $message = sprintf("Your quiz's length is bigger than %d !", Quiz::MAX_LENGTH);
                FlashMessage::error($message);

                header("Location: $url");
                exit;
            }
        }
    }

    /**
     * @param int $id
     */
    public function show(int $id)
    {

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
        // We know the that we should sort the questions types in the database when calling :all()
        // but we would the the primaryKey field from the models (which is not static). If we had more time,
        // we would create a query builder that would make our life easier and our code cleaner.
        usort($question_types, function ($qt1, $qt2) {
            // two question types cannot have the same id, that is why we do not return 0
            return $qt1->id > $qt2->id ? 1 : -1;
        });


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

        if (!$quiz->isEditable()) {
            FlashMessage::error("You can't edit a non building quiz!");

            $url = "/";
            header("Location: $url");
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
            FlashMessage::error("Quiz wasn't found!");

            header("Location: $url");
            exit;
        }

        if (!$quiz->isRemovable()) {
            FlashMessage::error("You cannot delete this quiz! Make sure your quiz is in answering mode!");

            header("Location: $url");
            exit;
        }

        $quiz->delete();

        FlashMessage::success("Quiz was successfully deleted!");

        header("Location: $url");
    }

    public function admin()
    {
        $data = [];

        // set title
        $data["head"]["title"] = "Looper";

        // load quiz list into view
        $data["body"]["quiz_list"]["building"]  = Quiz::buildingList();
        $data["body"]["quiz_list"]["answering"] = Quiz::answeringList();
        $data["body"]["quiz_list"]["closed"]    = Quiz::closedList();

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
        $questions = null;
        $data = [];

        $url = "/quiz/admin";
        $quiz = Quiz::find($id);

        if ($quiz === null) {
            FlashMessage::error("Quiz wasn't found!");

            header("Location: $url");
            exit;
        }

        if (!$quiz->canBeAnswered()) {
            FlashMessage::error("Your quiz has to be in building mode!");

            header("Location: $url");
            exit;
        }

        $nextState = $quiz->state()->next();
        if ($nextState !== null) {
            $quiz->quiz_state_id = $nextState->id;
        } else {
            FlashMessage::error("Quiz state wasn't found!");

            header("Location: $url");
            exit;
        }

        $questions = $quiz->questions();
        if (count($questions) <= 0) {
            $message = "This quiz as no questions!<br>";
            $message .= "Before moving it to answering, add some questions!";
            FlashMessage::error($message);

            header("Location: $url");
            exit;
        }
        $quiz->save();

        FlashMessage::success("Quiz was successfully changed to Answering!");

        header("Location: $url");
    }

    public function toClosed(int $id)
    {
        $quiz = null;
        $url = "/quiz/admin";

        $quiz = Quiz::find($id);

        if ($quiz === null) {
            FlashMessage::error("Quiz wasn't found!");

            header("Location: $url");
            exit;
        }

        if (!$quiz->canBeClosed()) {
            FlashMessage::error("Your quiz has to be in answering mode!");

            header("Location: $url");
            exit;
        }

        $nextState = $quiz->state()->next();
        if ($nextState !== null) {
            $quiz->quiz_state_id = $nextState->id;
        } else {
            FlashMessage::error("Quiz state wasn't found!");

            header("Location: $url");
            exit;
        }
        $quiz->save();

        FlashMessage::success("Quiz was successfully closed");

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
        $quizFulfillments = $quiz->fulfillments();

        $data["body"]["fulfillments"] = $quizFulfillments;

        // set title
        $data["head"]["title"] = "Results";

        // get css stylesheets
        ob_start();
        require_once("resources/views/admin/style.php");
        $data["head"]["css"] = ob_get_clean();

        // set header title (next to the logo)
        $data["header"]["title"] = "Exercise: {$quiz->title}";

        // get body content
        ob_start();
        require_once("resources/views/templates/header.php");
        require_once("resources/views/admin/results.php");
        $data["body"]["content"] = ob_get_clean();

        // finally, render page
        $this->view->render("templates/base.php", $data);
    }
}