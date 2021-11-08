<div class="pure-g parent-center">
    <div id="questions_list_parent" class="pure-u-1-2 wrapper">
        <table class="pure-table pure-table-horizontal">
            <thead>
            <tr>
                <th>Take</th>
                <th><a><?= $quiz->title ?></a></th>
            </tr>
            </thead>

            <tbody>
            <?php foreach ($quizFulfillments as $fulfillment): ?>
                <tr>
                    <td><a href="/fulfillment/<?= $fulfillment->fulfillment_id ?>"><?= $fulfillment->date ?></a></td>
                    <td class="answer"><i class="fa fa-check short"></i></td>
                </tr>
            <?php endforeach; ?>
            </tbody>

        </table>
    </div>
</div>
