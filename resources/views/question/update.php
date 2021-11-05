<div class="pure-g parent-center">
    <div id="update_question_parent" class="pure-u-1-3 wrapper">
        <form class="pure-form pure-form-stacked" action="/question/<?= $data["body"]["question"]->id ?>" method="post">
            <fieldset>
                <legend>Editing: <?= $data["body"]["question"]->label ?></legend>
                <fieldset class="pure-group">
                    <label for="question_label">Label</label>
                    <input type="text" id="question_label" class="pure-u-1-1" required="required" placeholder="Label"
                           value="<?= $data["body"]["question"]->label ?>"/>
                </fieldset>

                <fieldset class="pure-group">
                    <label for="question_type">Value type</label>
                    <select id="question_type">
                        <?php foreach ($data["body"]["question_types"] as $type): ?>
                            <option <?php if ($question->type()->label === $type->label) : ?>
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
        </form>
    </div>
</div>