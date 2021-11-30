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

<div id="new_quiz_parent" class="pure-g">
    <form class="pure-u-3-5 pure-form pure-form-stacked" action="/quiz/<?= $data["body"]["quiz"]->id ?>/fullfilment"
          method="post">
        <fieldset>
            <legend>Your Take</legend>
            <div>If you'd like to come back later to finish, simply submit it with blanks.</div>
            <br>
            <?php foreach ($data["body"]["questions"] as $question): ?>
                <label for="quiz_title"><?= $question->label ?></label>
                <?php if ($question->type()->label === "Single line text"): ?>
                    <input name="<?= $question->id ?>" type="text" class="pure-u-1-1"
                           placeholder="Your answer.."/>
                    <br>
                <?php else: ?>
                    <textarea name="<?= $question->id ?>" class="pure-u-1-1"
                              placeholder="Your answer.."></textarea>
                    <br>
                <?php endif; ?>
            <?php endforeach; ?>

            <input type="hidden" id="csrf_token" name="csrf_token" value="<?= $data["body"]["csrf_token"] ?>"/>
            <button type="submit" class="pure-u-1-1 pure-button pure-button-primary">SAVE</button>
        </fieldset>
    </form>
</div>