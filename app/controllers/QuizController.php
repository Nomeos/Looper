<?php

namespace App\controllers;

use App\lib\http\HttpRequest;
use App\lib\ResourceController;
use App\models\Quiz;
use Exception;

class QuizController extends ResourceController
{
    /**
     * @throws Exception
     */
    public function index()
    {
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
        // TODO: Implement store() method.
    }

    /**
     * @param int $id
     * @throws Exception
     */
    public function edit(int $id)
    {
        $data = [];

        // set title
        $data["head"]["title"] = "Edit QUIZ NAME";

        // get css stylesheets
        ob_start();
        require_once("resources/views/quiz/style.php");
        require_once("resources/views/question/style.php");
        $data["head"]["css"] = ob_get_clean();

        // set header title (next to the logo)
        $data["header"]["title"] = "QUIZ NAME";

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
        $quiz_list = Quiz::all();
        $data = [];

        // set title
        $data["head"]["title"] = "Looper";

        // load quiz list into view
        $data["body"]["quiz_list"]["building"] = array_filter($quiz_list, function($quiz) {
            return $quiz->state()->label === "Building";
        });
        $data["body"]["quiz_list"]["answering"] = array_filter($quiz_list, function($quiz) {
            return $quiz->state()->label === "Answering";
        });
        $data["body"]["quiz_list"]["closed"] = array_filter($quiz_list, function($quiz) {
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
}