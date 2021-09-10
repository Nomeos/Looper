<?php

require_once(sprintf("%s/config/config.php", dirname($_SERVER['DOCUMENT_ROOT'])));
require_once("app/controllers/LooperController.php");

function main()
{
    $controller = new LooperController();
    $controller->index();
}

main();