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

<div class="wrapper pure-g">
    <?php foreach ($data["body"]["quiz_list"]["answering"] as $quizzes) : ?>
        <div class="quiz pure-u-2-3">
            <label for="take_quiz"><?= $quizzes->title ?></label>
            <a class=" pure-button pure-button-primary upper-case" href="/quiz/<?= $quizzes->id ?>/fullfilment">Take a
                quiz</a>
        </div>
    <?php endforeach; ?>

</div>