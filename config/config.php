<?php

define('PROJECT_ROOT', dirname($_SERVER["DOCUMENT_ROOT"]));
define('VIEWS_ROOT', sprintf("%s/resources/views", PROJECT_ROOT));
define('MIN_CHARACTER_LENGTH', '0.0');
define('MAX_CHARACTER_LENGTH', '10.0');

// set project's root as include path
set_include_path(PROJECT_ROOT);