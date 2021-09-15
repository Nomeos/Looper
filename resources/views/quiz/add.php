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
    <link rel="stylesheet" href="/assets/css/quiz/style.css">
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

<div id="new_quiz_parent" class="pure-g">
    <form class="pure-u-3-5 pure-form pure-form-stacked">
        <fieldset>
            <legend>Create a new quiz</legend>
            <label for="quiz_title">Title</label>
            <input type="text" id="quiz_title" class="pure-u-1-1" required placeholder="Title"/>
            <button type="submit" class="pure-u-2-5 pure-button pure-button-primary">ADD</button>
        </fieldset>
    </form>
</div>

</body>
</html>