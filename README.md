<!--
*** Thanks for checking out the Best-README-Template. If you have a suggestion
*** that would make this better, please fork the repo and create a pull request
*** or simply open an issue with the tag "enhancement".
*** Thanks again! Now go create something AMAZING! :D
***
***
***
*** To avoid retyping too much info. Do a search and replace for the following:
*** github_username, repo_name, twitter_handle, email, project_title, project_description
-->



<!-- PROJECT SHIELDS -->
<!--
*** I'm using markdown "reference style" links for readability.
*** Reference links are enclosed in brackets [ ] instead of parentheses ( ).
*** See the bottom of this document for the declaration of the reference variables
*** for contributors-url, forks-url, etc. This is an optional, concise syntax you may use.
*** https://www.markdownguide.org/basic-syntax/#reference-style-links
-->
[![Forks][forks-shield]][forks-url]
[![Stargazers][stars-shield]][stars-url]
[![Issues][issues-shield]][issues-url]
[![MIT License][license-shield]][license-url]


<!-- PROJECT LOGO -->
<br />
<p align="center">
  <a href="https://github.com/Nomeos/Looper">
    <img src="public/assets/img/logo.png" alt="Logo" width="80" height="80">
  </a>

  <h3 align="center">Quiz looper</h3>

  <p align="center">
    A small webapp written in PHP that follows the MVC pattern.<br/>
    Its goals are to create, manage and delete quizzes.<br/>
    Users can also get some statistics related to the answers.
    <br />
    <a href="./doc"><strong>Explore the docs »</strong></a>
    <br />
    <br />
    <a href="https://github.com/Nomeos/Looper">View Demo</a>
    ·
    <a href="https://github.com/Nomeos/Looper/issues">Report Bug</a>
    ·
    <a href="https://github.com/Nomeos/Looper/issues">Request Feature</a>
  </p>
</p>



<!-- TABLE OF CONTENTS -->
<details open="open">
  <summary><h2 style="display: inline-block">Table of Contents</h2></summary>
  <ol>
    <li>
      <a href="#about-the-project">About The Project</a>
      <ul>
        <li><a href="#built-with">Built With</a></li>
        <li><a href="#architecture">Architecture</a></li>
        <ul>
          <li><a href="#libraries">Libraries</a></li>
          <li><a href="#standard-techniques-used-in-this-project">Standard techniques used in this project</a></li>
          <ul>
            <li><a href="#view-rendering">View rendering</a></li>
            <li><a href="#csrf-protection">CSRF protection</a></li>
            <li><a href="#http-requests">HTTP requests</a></li>
            <li><a href="#message-display">Message display</a></li>
            <li><a href="#database-connection">Database connection</a></li>
            <li><a href="#seeders">Seeders</a></li>
          </ul>
        </ul>
      </ul>
    </li>
    <li>
      <a href="#features">Features</a>
    </li>
    <li>
      <a href="#getting-started">Getting Started</a>
      <ul>
        <li><a href="#prerequisites">Prerequisites</a></li>
        <li><a href="#installation">Installation</a></li>
      </ul>
    </li>
    <li><a href="#hacking-on-the-project">Hacking on the project</a></li>
    <li><a href="#hacks-used-in-this-project">Hacks used in this project</a></li>
    <ul>
      <li><a href="#css">Css</a></li>
      <li><a href="#http-requests">Http requests</a></li>
    </ul>
    <li><a href="#roadmap">Roadmap</a></li>
    <li><a href="#documentation">Documentation</a></li>
    <li><a href="#contributing">Contributing</a></li>
    <li><a href="#license">License</a></li>
    <li><a href="#contact">Contact</a></li>
  </ol>
</details>



<!-- ABOUT THE PROJECT -->
## About The Project
This project allows users to create quizzes, answer questions and look at the statistics. There are 3 types of quizzes: building, answering and closed.

A building quiz is a quiz to which questions can be added and which can be deleted. An answering quiz can only be closed and show statistics. Finally, a closed quiz can be deleted or show statistics.

There are also 3 types of questions: single line text, list of single lines and multi-line text. The only difference between them is the size of the questions labels.

![List of quizzes](public/assets/img/types-of-quizzes.png)

![List of questions](public/assets/img/types-of-questions.png)


### Built With

* [PHP 8.0](https://www.php.net/releases/8.0/en.php)
* [Mariadb 10.6.4](https://mariadb.com/kb/en/mariadb-1064-release-notes/)
* [Jquery 3.6](https://jquery.com/download/)
* [Dart Sass 1.45.0](https://sass-lang.com/dart-sass)
* [Composer 2.1.11](https://getcomposer.org/download/)


### Architecture
Like most web applications today, this project uses the MVC model. Interactions with the database are done by templates, html rendering is done by views and all logic is handled by controllers.

#### Libraries
We also used a routing system that makes the URLs look nicer. The library we used is called [FastRoute](https://github.com/nikic/FastRoute). It is a simple routing library that allows us to map URLs to php functions (controller methods). We used [resource controllers just like Laravel](https://laravel.com/docs/8.x/controllers#actions-handled-by-resource-controller) to handle CRUD requests. Like laravel, routes are generated for each resource controller. We did this thanks to our [RouteFactory](doc/classes/classes.pdf). For further details, check [our routes diagram](doc/routes/routes.pdf).

For the database modifications, we used [this](https://github.com/byjg/migration) library. It allows us to easily modify our database as most framework migration systems do. We used it because it was much easier to empty the database before doing the unit tests.

For unit testing, we used [the standard PHP library for unit tests](https://phpunit.de/).

We did not use a template engine or ORM. Instead, we developed [our own](https://github.com/Thynkon/simple-orm). At the end of this project, we realized that we should have created a query builder library to facilitate queries of the template database.

#### Standard techniques used in this project

All classes mentionned above can be found [here](doc/classes/classes.pdf)

##### View rendering
Like most of modern web applications, we use views in order to follow the [separation of concerns pattern](https://en.wikipedia.org/wiki/Separation_of_concerns).

We created a class called `View` that takes care of rendering a template file with some additional information that we can pass to the view.

##### CSRF protection
As you might know, CSRFs are a commun practice used by crackers to manipulate http requests. In order to prevent those attacks, we implemented [CSRF tokens](https://portswigger.net/web-security/csrf/tokens).

Before rendering forms, we gerenate a random token and store it in the user's session. Once the token generated, we then display it as an hidden input field. So when a request is made, we check if the providen token is the same stored in the user's session. If it isn't, a proper error message is displayed.

We talk about how we display messages to the user [here]()

##### HTTP requests
Handling http requests in PHP without using a framework can be a demanding task. Checking wether we should use `$_GET` or `$_POST` to get data from the request is not the best OOP approach.

Thus, we decided to create a `HttpRequest` class that encapsulates a http request. Instead of doing something like
```php
$_POST["text"]
```

we do this
```php
$request->getBodyData()["text"];
```

We can also retrieve the protocol's version as well as the request's uri.

##### Message display
In order to easily display message to the user, we implemented a technique called [flash message](https://laravel.com/docs/8.x/session#flash-data). It consists of storing the message in the user's session and displaying it in the view.

Here's an example of how to display a message:

- On your controller:
  ```php
  FlashMessage::error("YOUR MESSAGE");
  ```

- On your view:
  ```php
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
  ```

##### Database connection
We use an ORM to fetch data from the database. The ORM we use implements the [singleton pattern](https://refactoring.guru/design-patterns/singleton). By implementing it, we are able to drastically reduce the number of database connections since we always return the same connection while handling a http request.

##### Seeders
During the development of this project, we used dummy data in order to be able to set up unit tests. Again, as with most web frameworks, we used seeders to feed our database.

All the seeders we used implement an interface that requirements all classes to have a method that populates the database. If you are not familiar with this concept, you may want to read [laravel's documentation](https://laravel.com/docs/8.x/seeding)

## Features
A list of all features implemented in this project can be found [here](features.md).

<!-- GETTING STARTED -->
## Getting Started

To get a local copy up and running follow these simple steps.

### Prerequisites
#### [Archlinux](https://archlinux.org)
- [MariaDB](https://wiki.archlinux.org/title/MariaDB#Installation)
  ```sh
  sudo pacman -S mariadb
  sudo mariadb-install-db --user=mysql --basedir=/usr --datadir=/var/lib/mysql
  ```
- [PHP](https://www.php.net/)
  ```sh
  sudo pacman -S php
  ```

- [Composer](https://getcomposer.org)
  ```sh
  sudo pacman -S composer
  ```

- [Npm](https://www.npmjs.com)
  ```sh
  sudo pacman -S nodejs
  ```

#### [NixOS](https://nixos.org/)
  ```sh
  nix-shell shell.nix
  ```

#### [Windows](https://www.microsoft.com/fr-ch/windows)
- [Chocolatey](https://chocolatey.org/install)
Just need to follow the steps in Choco's website
  ```sh
  - Get-ExecutionPolicy
  - Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://community.chocolatey.org/install.ps1'))
  ```
- [PHP](https://www.php.net/)
  ```sh
  choco install php
  ```
- [Composer](https://getcomposer.org)
  ```sh
  choco install composer
  ```

- [MariaDB](https://mariadb.org/)
  ```sh
  choco install mariadb
  ```
- [Npm](https://www.npmjs.com)
  ```sh
  choco install npm
  ```
  

### Installation

1. Clone the repo
   ```sh
   git clone https://github.com/Nomeos/Looper.git
   ```
2. Install php packages
   ```sh
   cd Looper
   composer install
   ```

3. Install npm dependencies
    ```sh
    npm install -g sass
    ```
   
4. Compile scss files to css

   On UNIX-like operating systems:

   ```sh
   composer build-css
   ```

   On Windows

   ```sh
   pip install -r requirements.txt
   ```

   ```sh
   composer build-css-win
   ```

5. Setup database connection
This projects uses PDO as the database connector. In order to connect to a database, you must
set the DSN, the username and his password in **.env.php**.
   ```php
   <?php

   DEFINE('DSN', 'mysql:dbname=<YOUR_DATABASE_NAME>;host=127.0.0.1');
   DEFINE('USERNAME', '<USERNAME>');
   DEFINE('PASSWORD', '<PASSWORD>');
   ```

6. Populate the database
   ```sh
   composer populate-db
   ```

## Hacking on the project
### Tests
If you have added a feature and you want to test if everything is ok, you can run the unit tests we wrote
by typing the following:
```sh
composer test
```

## Hacks used in this project
### Css
As you can see in every folder under **resources/<folder_name>** there is a file called **style.php**.
This file is used to only load the stylesheets required by a specific view. So, every time we want to
render a view, we have to load it and store id in **$data["head"]["css"]**.

The stylesheet is stored under 'head' because html link tags can only be at html request's header.
For data that should be in header, I store it in **$data["head"]** and for data that should be in the
response's body, I store it in **$data["body"]**.
```php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $data["head"]["title"] ?></title>
    <link rel="stylesheet" href="https://unpkg.com/purecss@2.0.6/build/pure-min.css"
          integrity="sha384-Uu6IeWbM+gzNVXJcM9XV3SohHtmWE+3VGi496jvgX1jyvDTXfdK+rfZc8C1Aehk5" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/purecss@2.0.6/build/grids-responsive-min.css">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="/assets/css/lib/lib.css"/>

    <?= $data["head"]["css"] ?>
</head>
<body>
<?= $data["body"]["content"] ?>
</body>
</html>
```

### Http requests
[According to the html standard](https://stackoverflow.com/a/8054241), forms can only send GET and POST requests. To get around this limitation, we created a hidden input field that contains the http method. This practice has been adopted by most frameworks like Laravel and Symphony.

```html
<form method="post">
  <input type="hidden" name="_method" value="put" />
</form>
```

We also added an event listener on the submit form that receives the form's http method and makes an ajax request. This way we can still make PUT requests.

```js
$('form').submit(function (event) {
    event.preventDefault();

    let current_url = window.location.href;

    // Get all the forms elements and their values in one step
    let form_data_array = $(this).serializeArray();
    let form_data = $(this).serialize();
    let http_method = form_data_array.find(o => o.name === '_method').value;

    $.ajax({
        url: $(this).attr('action'),
        type: http_method,
        data: form_data,
        success: function (response) {
            window.location.replace(current_url);
        }
    });
});
```

In some cases, like the admin view, we have used a data-XXX property to specify the http method instead of using an hidden input because it would be useless to have a form with only an hidden input.

```js
const buttons = document.querySelectorAll('a[data-method]');
    let url = window.location.href;
    let publish_quiz_button_id = "publish_quiz";

    buttons.forEach(function (button) {
        button.addEventListener('click', function (event) {
            // tell the browser not to respond to the link click
            event.preventDefault();

            if (button.id === publish_quiz_button_id) {
                url = "/quiz/admin";
            }

            if (confirm('Are you sure?')) {
                doRequest(button)
                    .then(function (response) {
                        if (response.status !== 200) {
                            console.log("ERROR");
                            // ADD ERROR MESSAGE TO HTML PAGE
                        } else {
                            window.location.replace(url);
                        }
                    })
                    .catch(error => console.log("ERROR"));
            } else {
                window.location.href = url;
            }
        });
    });
```

<!-- ROADMAP -->
## Roadmap

See the [open issues](https://github.com/Nomeos/Looper/issues) for a list of proposed features (and known issues).

## Documentation
The documentation about the routing system, class diagrams, database model and the state diagram can be found under the **doc/** folder:
- [routing system](doc/routes/routes.pdf)
- [class diagrams](doc/classes/classes.pdf)
- database
  - [conceptual data model](doc/db/cdm/diagram.pdf)
  - [logical data model](doc/db/ldm/diagram.pdf)
- [state diagram](doc/state_diagram/state_diagram.pdf)
- [retrospective](doc/retrospective/Retrospective.pdf)

We use the [same directory structure as laravel](https://laravel.com/docs/8.x/structure).

<!-- CONTRIBUTING -->
## Contributing

Contributions are what make the open source community such an amazing place to learn, inspire, and create. Any contributions you make are **greatly appreciated**.

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request



<!-- LICENSE -->
## License

Distributed under the MIT License. See [`LICENSE`](LICENSE) for more information.



<!-- CONTACT -->
## Contact

Project Link: [https://github.com/Nomeos/Looper](https://github.com/Nomeos/Looper)

<!-- MARKDOWN LINKS & IMAGES -->
<!-- https://www.markdownguide.org/basic-syntax/#reference-style-links -->
[forks-shield]: https://img.shields.io/github/forks/Nomeos/Looper
[forks-url]: https://github.com/Nomeos/Looper/network/members
[stars-shield]: https://img.shields.io/github/stars/Nomeos/Looper
[stars-url]: https://github.com/Nomeos/Looper/stargazers
[issues-shield]: https://img.shields.io/github/issues/Nomeos/Looper
[issues-url]: https://github.com/Nomeos/Looper/issues
[license-shield]: https://img.shields.io/github/license/Nomeos/Looper
[license-url]: https://github.com/Nomeos/Looper/blob/master/LICENSE
