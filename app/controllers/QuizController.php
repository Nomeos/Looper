<?php

require_once("app/controllers/Controller.php");
require_once("app/lib/View.php");

class QuizController implements  Controller
{
    public function index()
    {
        $view = new View();
        $data = [];

        // set title
        $data["head"]["title"] = "Looper";

        // get css stylesheets
        ob_start();
        require_once("resources/views/home/style.php");
        $data["head"]["css"] = ob_get_clean();

        // get body content
        ob_start();
        require_once("resources/views/home/home.php");
        $data["body"]["content"] = ob_get_clean();

        // finally, render page
        $view->render("templates/base.php", $data);
    }

    public function create()
    {
        $view = new View();
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
        $view->render("templates/base.php", $data);
    }

    public function store()
    {
        // TODO: Implement store() method.
    }

    public function show()
    {
        $view = new View();
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
        $view->render("templates/base.php", $data);
    }

    public function edit()
    {
        // TODO: Implement edit() method.
    }

    public function update()
    {
        // TODO: Implement update() method.
    }

    public function destroy()
    {
        // TODO: Implement destroy() method.
    }
}