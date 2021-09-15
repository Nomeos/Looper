<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Exercise Looper</title>
    <link rel="stylesheet" href="https://unpkg.com/purecss@2.0.6/build/pure-min.css"
          integrity="sha384-Uu6IeWbM+gzNVXJcM9XV3SohHtmWE+3VGi496jvgX1jyvDTXfdK+rfZc8C1Aehk5" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/purecss@2.0.6/build/grids-responsive-min.css">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet"/>

    <link rel="stylesheet" href="/assets/css/quiz/style.css">
    <link rel="stylesheet" href="/assets/css/question/style.css">
</head>

<body>
    <header class="pure-g">
        <div class="pure-u">
            <img id="logo" class="pure-img" src="/assets/img/logo.png">
        </div>
        <div id="quiz_title" class="pure-u">
            Exercise Looper
        </div>
    </header>

    <div class="pure-g parent-center">
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
                <tr>
                    <td>1</td>
                    <td>Honda</td>
                    <td class="action">
                        <a title="Edit" href="/exercises/422/fields/618/edit"><i class="fa fa-edit"></i></a>
                        <a data-confirm="Are you sure?" title="Destroy" rel="nofollow" data-method="delete" href="/exercises/422/fields/618"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Toyota</td>
                    <td class="action">
                        <a title="Edit" href="/exercises/422/fields/618/edit"><i class="fa fa-edit"></i></a>
                        <a data-confirm="Are you sure?" title="Destroy" rel="nofollow" data-method="delete" href="/exercises/422/fields/618"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Hyundai</td>
                    <td class="action">
                        <a title="Edit" href="/exercises/422/fields/618/edit"><i class="fa fa-edit"></i></a>
                        <a data-confirm="Are you sure?" title="Destroy" rel="nofollow" data-method="delete" href="/exercises/422/fields/618"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
                </tbody>
            </table>
            <a data-confirm="Are you sure? You won't be able to further edit this exercise" id="publish_quiz" class="pure-input-2-5 pure-button pure-button-primary upper-case" rel="nofollow" data-method="put" href="/exercises/422?exercise%5Bstatus%5D=answering"><i class="fa fa-comment"></i> Complete and be ready for answers</a>
        </div>

        <div id="new_question_parent" class="pure-u-1-3 wrapper">
            <form class="pure-form pure-form-stacked">
                <fieldset>
                    <legend>New Question</legend>
                    <fieldset class="pure-group">
                        <label for="question_label">Label</label>
                        <input type="text" id="question_label" class="pure-u-1-1" required="required" placeholder="Label"/>
                    </fieldset>

                    <fieldset class="pure-group">
                        <label for="question_type">Value type</label>
                        <select id="question_type">
                            <option selected="selected">Single line text</option>
                            <option>List of single lines</option>
                            <option>Multi-line text</option>
                        </select>
                    </fieldset>

                    <button id="add_question" type="submit" class="pure-input-2-5 pure-button pure-button-primary upper-case">Add question</button>
                </fieldset>
            </form>
        </div>

    </div>
    </body>
</html>