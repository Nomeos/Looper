<?php

require_once("app/lib/ResourceController.php");

class LooperController
{
    private $view;

    public function __construct()
    {
        $this->view = new View();
    }

    public function index()
    {
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
        $this->view->render("templates/base.php", $data);
    }
}