<?php
use App\lib\FlashMessage;
$message = FlashMessage::get();
?>

<?php if (is_array($message)): ?>
    <div class="flash_message <?= $message["type"] === FlashMessage::OK ? "success" : "error" ?>">
        <div>
            <i class="fa <?= $message["type"] === FlashMessage::OK ? "fa-check" : "fa-times-circle"?>"></i>
            <?= $message["value"]?>
        </div>
    </div>
<?php endif;?>

<header class="pure-g">
    <div class="pure-u">
        <img class="pure-img" src="/assets/img/logo.png">
    </div>
    <h1 class="pure-u">
        Exercise
        <br>
        Looper
    </h1>
</header>

<div id="quiz_buttons_parent" class="pure-g">
    <a id="take_quiz" class="pure-u-1-5 pure-button pure-button-primary upper-case" href="/quiz/answering">Take a quiz</a>
    <a id="create_quiz" class="pure-u-1-5 pure-button pure-button-primary upper-case" href="/quiz/create">Create a quiz</a>
    <a id="manage_quiz" class="pure-u-1-5 pure-button pure-button-primary upper-case" href="/quiz/admin">Manage a quiz</a>
</div>