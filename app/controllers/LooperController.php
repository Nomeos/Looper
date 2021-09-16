<?php

require_once("app/lib/View.php");

class LooperController
{
    public function index()
    {
        $view = new View();
        $data = [];

        // set title
        $data["head"]["title"] = "Edit a quiz";

        // get css stylesheets
        ob_start();
        require_once("resources/views/quiz/style.php");
        require_once("resources/views/question/style.php");
        $data["head"]["css"] = ob_get_clean();

        // set header title (next to the logo)
        $data["header"]["title"] = "New question";

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
}