<div class="wrapper pure-g">
    <?php foreach ($data["body"]["quiz_list"]["answering"] as $quizzes) : ?>
        <div class="quiz pure-u-2-3">
            <label for="take_quiz"><?= $quizzes->title ?></label>
            <a class=" pure-button pure-button-primary upper-case" href="/answer/<?= $quizzes->id ?>/edit">Take a
                quiz</a>
        </div>
    <?php endforeach; ?>

</div>