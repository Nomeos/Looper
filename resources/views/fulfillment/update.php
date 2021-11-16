<?php
use App\lib\FlashMessage;
$message = FlashMessage::get();
?>

<div class="flash_message <?= is_array($message) ? ($message["type"] === FlashMessage::OK ? "ok" : "error") : "" ?>"><?= is_array($message) ? $message["value"] : ""?></div>

<div id="new_quiz_parent" class="pure-g">
    <form class="pure-u-3-5 pure-form pure-form-stacked" action="/fulfillment/<?= $data["body"]["fulfillment"]->id ?>"
          method="post">
        <fieldset>
            <legend>Your Take</legend>
            <div>If you'd like to come back later to finish, simply submit it with blanks.</div>
            <br>
            <?php foreach ($data["body"]["fulfillment"]->answers() as $answer): ?>
                <label for="quiz_title"><?= $answer->question()->label ?></label>
                <?php if ($answer->question()->type()->label === "Single line text"): ?>
                    <input name="<?= $answer->id ?>" type="text" class="pure-u-1-1"
                           value="<?= $answer->value ?>"
                           placeholder="Your answer.."/>
                    <br>
                <?php else: ?>
                    <textarea name="<?= $answer->id ?>" class="pure-u-1-1"
                              placeholder="Your answer.."><?= $answer->value ?></textarea>
                    <br>
                <?php endif; ?>
            <?php endforeach; ?>
            <input type="hidden" name="_method" value="PUT">
            <button type="submit" class="pure-u-1-1 pure-button pure-button-primary">SAVE</button>
        </fieldset>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="/assets/js/update.js"></script>