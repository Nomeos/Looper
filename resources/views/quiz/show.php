<?php

use App\lib\FlashMessage;

$message = FlashMessage::get();
?>

<?php if (is_array($message)): ?>
    <div class="flash_message <?= $message["type"] === FlashMessage::OK ? "success" : "error" ?>">
        <div>
            <i class="fa <?= $message["type"] === FlashMessage::OK ? "fa-check" : "fa-times-circle" ?>"></i>
            <?= $message["value"] ?>
        </div>
    </div>
<?php endif; ?>

<div class="pure-g parent-center">
    <div id="questions_list_parent" class="pure-u-1-2 wrapper">
        <table class="pure-table pure-table-horizontal">
            <thead>
            <tr>
                <th>Take</th>
                <th>Content</th>
            </tr>
            </thead>

            <tbody>
            <?php foreach ($answers as $answer): ?>
                <tr>
                    <td><a href="/fulfillment/<?= $answer->fulfillment()->id ?>"><?= $answer->fulfillment()->date ?>
                            UTC</a>
                    </td>
                    <td class="answer"><?= $answer->value ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>

        </table>
    </div>
</div>
