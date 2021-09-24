<div id="new_question_parent" class="pure-u-1-3 wrapper">
    <form class="pure-form pure-form-stacked" action="/question/create" method="post">
        <fieldset>
            <legend>New Question</legend>
            <fieldset class="pure-group">
                <label for="question_label">Label</label>
                <input type="text" id="question_label" class="pure-u-1-1" name="question_label" required="required" placeholder="Label"/>
            </fieldset>

            <fieldset class="pure-group">
                <label for="question_type">Value type</label>
                <select id="question_type" name="question_type">
                    <option selected="selected">Single line text</option>
                    <option>List of single lines</option>
                    <option>Multi-line text</option>
                </select>
            </fieldset>

            <button id="add_question" type="submit" class="pure-input-2-5 pure-button pure-button-primary upper-case">Add question</button>
        </fieldset>
    </form>
</div>