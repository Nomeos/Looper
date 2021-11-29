<?php require_once('config/config.php'); ?>
<div class="pure-g parent-center">
    <div id="questions_list_parent" class="pure-u-1-2 wrapper">
        <table class="pure-table pure-table-horizontal">
            <thead>
            <tr>
                <th>Take</th>
                <?php foreach ($quiz->questions() as $question): ?>
                    <th><a href="/question/<?= $question->id ?>"><?= $question->label ?></a></th>
                <?php endforeach; ?>
            </tr>
            </thead>

            <tbody>
            <?php foreach ($data["body"]["fulfillments"] as $fulfillment): ?>
                <tr>
                    <td>
                        <a href="/fulfillment/<?= $fulfillment->id ?>"><?= $fulfillment->date ?> UTC</a>
                    </td>
                    <?php foreach ($fulfillment->answers() as $answer) : ?>
                        <?php $value = strlen($answer->value); ?>
                        <?php if ($value === MIN_CHARACTER_LENGTH): ?>
                            <td class="answer"><i class="fa fa-times short"></i></td>
                        <?php elseif ($value <= MAX_CHARACTER_LENGTH): ?>
                            <td class="answer"><i class="fa fa-check short"></i></td>
                        <?php else: ?>
                            <td class="answer"><i class="fa fa-check-double short"></i></td>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
