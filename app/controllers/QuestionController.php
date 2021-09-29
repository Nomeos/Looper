<?php

require_once("app/lib/ResourceController.php");

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

    public function store($request)
    {
        // TODO: Implement store() method.
    }

    public function show(int $id)
    {
        // TODO: Implement show() method.
    }

    public function edit(int $id)
    {
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
        $this->view->render("templates/base.php", $data);
    }

    public function update($request, $id)
    {
        // TODO: Implement update() method.
    }

    public function destroy(int $id)
    {
        // TODO: Implement destroy() method.
    }
}