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
                        <a title="Be ready for answers" href=""><i class="fa fa-comment"></i></a>
                        <a title="Edit" href="/quiz/<?= $quiz->id ?>/edit"><i class="fa fa-edit"></i></a>
                        <a id="delete1" data-confirm="Are you sure?" title="Destroy" rel="nofollow"
                           data-method="delete"
                           href="/quiz/1234"><i class="fa fa-trash"></i></a>
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
                        <a title="Be ready for answers" href=""><i class="fa fa-comment"></i></a>
                        <a title="Edit" href="/quiz/<?= $quiz->id ?>/edit"><i class="fa fa-edit"></i></a>
                        <a id="delete1" data-confirm="Are you sure?" title="Destroy" rel="nofollow"
                           data-method="delete"
                           href="/quiz/1234"><i class="fa fa-trash"></i></a>
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
                        <a title="Be ready for answers" href=""><i class="fa fa-comment"></i></a>
                        <a title="Edit" href="/quiz/<?= $quiz->id ?>/edit"><i class="fa fa-edit"></i></a>
                        <a id="delete1" data-confirm="Are you sure?" title="Destroy" rel="nofollow"
                           data-method="delete"
                           href="/quiz/1234"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="/assets/js/js.js"></script>