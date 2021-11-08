<div class="pure-g parent-center">
    <div id="questions_list_parent" class="pure-u-1-2 wrapper">

        <h1><?= $data["body"]["fulfillment"]->date ?> UTC</h1>
        <?php foreach ($data["body"]["answers"] as $answer): ?>
        <dl class="answer">
            <dt style="font-weight: bold"><?= $answer->question()->label ?></dt>
            <dd><?= $answer->value ?></dd>
        </dl>
        <?php endforeach; ?>
    </div>
</div>