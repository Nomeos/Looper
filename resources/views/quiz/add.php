<?php

use App\lib\FlashMessage;

$message = FlashMessage::get();
?>

<?php if (is_array($message)): ?>
    <div class="flash_message <?= $message["type"] === FlashMessage::OK ? "success" : "error" ?>">
        <div>
            <i class="fa <?= $message["type"] === FlashMessage::OK ? "fa-check" : "fa-times-circle" ?>"></i>
            <?= $message["value"] ?>
        </div>
    </div>
<?php endif; ?>

<div id="new_quiz_parent" class="pure-g">
    <form class="pure-u-3-5 pure-form pure-form-stacked" action="/quiz/store" method="post">
        <fieldset>
            <legend>Create a new quiz</legend>
            <label for="quiz_title">Title</label>
            <input type="text" id="quiz_title" class="pure-u-1-1" name="quiz_title" required placeholder="Title"/>

            <input type="hidden" id="csrf_token" name="csrf_token" value="<?= $data["body"]["csrf_token"] ?>"/>

            <button type="submit" class="pure-u-1-1 pure-button pure-button-primary upper-case">Create</button>
        </fieldset>
    </form>
</div>