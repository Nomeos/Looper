<div id="new_quiz_parent" class="pure-g">
    <form class="pure-u-3-5 pure-form pure-form-stacked" action="/quiz/<?= $data["body"]["quiz"]->id?>/fullfilment" method="post">
        <fieldset>
            <legend>Your Take</legend>
            <div>If you'd like to come back later to finish, simply submit it with blanks.</div>
            <br>
            <?php foreach ($questions as $question): ?>
                <label for="quiz_title"><?= $question->label ?></label>
                <input name="<?= $question->id ?>" type="text" id="quiz_title" class="pure-u-1-1" required
                       placeholder="Your answer.."/>
            <br>
            <?php endforeach; ?>
            <button type="submit" class="pure-u-2-5 pure-button pure-button-primary">SAVE</button>
        </fieldset>
    </form>
</div>