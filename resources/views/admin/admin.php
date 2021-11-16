<?php
use App\lib\FlashMessage;
$message = FlashMessage::get();
?>

<div class="flash_message <?= is_array($message) ? ($message["type"] === FlashMessage::OK ? "ok" : "error") : "" ?>"><?= is_array($message) ? $message["value"] : ""?></div>
<div class="pure-g parent-center">
    <div id="questions_list_parent" class="pure-u-1-4 wrapper">

        <table class="pure-table pure-table-horizontal">
            <caption>Building</caption>
            <thead>
            <tr>
                <th>Title</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($data["body"]["quiz_list"]["building"] as $quiz): ?>
                <tr>
                    <td><?= $quiz->title ?></td>
                    <td class="action">
                        <?php if (count($quiz->questions()) > 0) : ?>
                            <a data-method="put" title="Be ready for answers" href="/quiz/<?= $quiz->id ?>/toAnswering"><i
                                        class="fa fa-comment"></i></a>
                        <?php endif ?>
                        <a title="Edit" href="/quiz/<?= $quiz->id ?>/edit"><i class="fa fa-edit"></i></a>
                        <a id="delete1" data-confirm="Are you sure?" title="Destroy" rel="nofollow"
                           data-method="delete"
                           href="/quiz/<?= $quiz->id ?>"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div id="questions_list_parent" class="pure-u-1-4 wrapper">
        <table class="pure-table pure-table-horizontal">
            <caption>Answering</caption>
            <thead>
            <tr>
                <th>Title</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($data["body"]["quiz_list"]["answering"] as $quiz): ?>
                <tr>
                    <td><?= $quiz->title ?></td>
                    <td class="action">
                        <a title="Show results" href="/quiz/<?= $quiz->id ?>/results"><i
                                    class="fa fa-chart-bar"></i></a>
                        <a title="Close" rel="nofollow" data-method="put" href="/quiz/<?= $quiz->id ?>/toClosed">
                            <i class="fa fa-minus-circle"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div id="questions_list_parent" class="pure-u-1-4 wrapper">
        <table class="pure-table pure-table-horizontal">
            <caption>Closed</caption>
            <thead>
            <tr>
                <th>Title</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($data["body"]["quiz_list"]["closed"] as $quiz): ?>
                <tr>
                    <td><?= $quiz->title ?></td>
                    <td class="action">
                        <a title="Show results" href="/quiz/<?= $quiz->id ?>/fulfillment"><i
                                    class="fa fa-chart-bar"></i></a>
                        <a data-confirm="Are you sure?" title="Destroy" rel="nofollow" data-method="delete"
                           href="/quiz/<?= $quiz->id ?>"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="/assets/js/js.js"></script>