<div id="questions_list_parent" class="pure-u-1-3 wrapper">
    <table class="pure-table pure-table-horizontal">
        <caption>Questions list</caption>
        <thead>
        <tr>
            <th>Label</th>
            <th>Value type</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data["body"]["quiz"]->questions() as $question): ?>
        <tr>
            <td><?= $question->label ?></td>
            <td><?= $question->type()->label ?></td>
            <td class="action">
                <a title="Edit" href="/question/<?= $question->id ?>/edit"><i class="fa fa-edit"></i></a>
                <a data-confirm="Are you sure?" title="Destroy" rel="nofollow" data-method="delete" href="/question/<?= $question->id ?>"><i class="fa fa-trash"></i></a>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <a data-confirm="Are you sure? You won't be able to further edit this exercise" id="publish_quiz" class="pure-input-2-5 pure-button pure-button-primary upper-case" rel="nofollow" data-method="put" href="/exercises/422?exercise%5Bstatus%5D=answering"><i class="fa fa-comment"></i> Complete and be ready for answers</a>
</div>