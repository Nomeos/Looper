<?php

require_once("app/controllers/Controller.php");
require_once("app/lib/View.php");

class QuestionController implements Controller
{

    public function index()
    {
        // TODO: Implement index() method.
    }

    public function create()
    {
        // TODO: Implement create() method.
    }

    public function store()
    {
        // TODO: Implement store() method.
    }

    public function show()
    {
        // TODO: Implement show() method.
    }

    public function edit()
    {
        $view = new View();
        $data = [];

        // set title
        $data["head"]["title"] = "Edit QUESTION NAME";

        // get css stylesheets
        ob_start();
        require_once("resources/views/question/style.php");
        $data["head"]["css"] = ob_get_clean();

        // set header title (next to the logo)
        $data["header"]["title"] = "QUESTION NAME";

        // get body content
        ob_start();
        require_once("resources/views/templates/header.php");
        require_once("resources/views/question/update.php");
        $data["body"]["content"] = ob_get_clean();

        // finally, render page
        $view->render("templates/base.php", $data);
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