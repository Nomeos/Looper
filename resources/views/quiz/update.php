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

<div class="pure-g parent-center">
    <?= $data["body"]["questions_list"] ?>
    <?= $data["body"]["questions_add"] ?>
</div>

<script src="/assets/js/js.js"></script>