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

<div class="pure-g parent-center">
    <div id="update_question_parent" class="pure-u-1-3 wrapper">
        <form id="update_question_form" class="pure-form pure-form-stacked"
              action="/question/<?= $data["body"]["question"]->id ?>" method="post">
            <fieldset>
                <legend>Editing: <?= $data["body"]["question"]->label ?></legend>
                <fieldset class="pure-group">
                    <label for="question_label">Label</label>
                    <input type="text" id="question_label" name="question_label" class="pure-u-1-1" required="required"
                           placeholder="Label"
                           value="<?= $data["body"]["question"]->label ?>"/>
                </fieldset>

                <fieldset class="pure-group">
                    <label for="question_type">Value type</label>
                    <select id="question_type" name="question_type_id">
                        <?php foreach ($data["body"]["question_types"] as $type): ?>
                            <option value="<?= $type->id ?>" <?php if ($question->type()->label === $type->label) : ?>
                                selected="selected"
                            <?php endif ?>
                            ><?= $type->label ?></option>
                        <?php endforeach; ?>
                    </select>
                </fieldset>

                <button id="update_question" type="submit"
                        class="pure-input-2-5 pure-button pure-button-primary upper-case">Update question
                </button>
            </fieldset>

            <input type="hidden" name="_method" value="PUT">
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="/assets/js/update.js"></script>