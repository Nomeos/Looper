<?php
use App\lib\FlashMessage;
$message = FlashMessage::get();
?>

<div class="flash_message <?= is_array($message) ? ($message["type"] === FlashMessage::OK ? "ok" : "error") : "" ?>"><?= is_array($message) ? $message["value"] : ""?></div>

<div class="wrapper pure-g">
    <?php foreach ($data["body"]["quiz_list"]["answering"] as $quizzes) : ?>
        <div class="quiz pure-u-2-3">
            <label for="take_quiz"><?= $quizzes->title ?></label>
            <a class=" pure-button pure-button-primary upper-case" href="/quiz/<?= $quizzes->id ?>/fullfilment">Take a
                quiz</a>
        </div>
    <?php endforeach; ?>

</div>