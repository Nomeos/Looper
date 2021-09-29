<div class="pure-g parent-center">
    <div id="update_question_parent" class="pure-u-1-3 wrapper">
        <form class="pure-form pure-form-stacked" action="/exercises/444/fields/630" method="post">
            <fieldset>
                <legend>Editing Question</legend>
                <fieldset class="pure-group">
                    <label for="question_label">Label</label>
                    <input type="text" id="question_label" class="pure-u-1-1" required="required" placeholder="Label" value="QUESTION TITLE"/>
                </fieldset>

                <fieldset class="pure-group">
                    <label for="question_type">Value type</label>
                    <select id="question_type">
                        <option selected="selected">Single line text</option>
                        <option>List of single lines</option>
                        <option>Multi-line text</option>
                    </select>
                </fieldset>

                <button id="update_question" type="submit" class="pure-input-2-5 pure-button pure-button-primary upper-case">Update question</button>
            </fieldset>
        </form>
    </div>
</div>