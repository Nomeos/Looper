<?php
use App\lib\FlashMessage;
$message = FlashMessage::get();
?>

<header class="pure-g">
    <div class="pure-u">
        <a href="/">
            <img id="logo" class="pure-img" src="/assets/img/logo.png">
        </a>
    </div>
    <div id="quiz_title" class="pure-u">
        <?= $data["header"]["title"] ?>
    </div>

    <div class="pure-u flash_message <?= is_array($message) ? ($message["type"] === FlashMessage::OK ? "ok" : "error") : "" ?>"><?= is_array($message) ? $message["value"] : ""?></div>
</header>
