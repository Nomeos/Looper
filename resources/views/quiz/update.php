<?php
use App\lib\FlashMessage;
$message = FlashMessage::get();
?>

<div class="flash_message <?= is_array($message) ? ($message["type"] === FlashMessage::OK ? "ok" : "error") : "" ?>"><?= is_array($message) ? $message["value"] : ""?></div>

<div class="pure-g parent-center">
    <?= $data["body"]["questions_list"] ?>
    <?= $data["body"]["questions_add"] ?>
</div>