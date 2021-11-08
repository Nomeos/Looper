<?php
namespace App\models;

use Thynkon\SimpleOrm\database\DB;
use Thynkon\SimpleOrm\Model;

class Quiz extends Model
{
    public static string $table = "quizzes";
    protected string $primaryKey = "id";
    public int $id;
    public string $title;
    // int should be boolean but, for no reason, PDO (exec function) converts
    // false to '' (empty string) instead of to 0, (which it does
    // for when value is true!!!!)
    // https://stackoverflow.com/a/64874581
    public int $is_public;
    public int $quiz_state_id;

    public function state()
    {
        $query = <<< EOL
SELECT quiz_states.*
FROM quizzes
INNER JOIN quiz_states ON quiz_states.id = quizzes.quiz_state_id
WHERE quizzes.id = :id;
EOL;

        $database = DB::getInstance();
        return $database->selectOne($query, ["id" => $this->id], QuizState::class);
    }

    public function questions()
    {
        $query = <<< EOL
SELECT *
FROM questions
WHERE quiz_id = :id;
EOL;

        $database = DB::getInstance();
        return $database->selectMany($query, ["id" => $this->id], Question::class);
    }
    public function fulfillments(int $id)
    {
        $query = <<< EOL

SELECT * FROM fulfillments
INNER JOIN answers ON answers.fulfillment_id = fulfillments.id
INNER JOIN questions ON questions.id = answers.question_id
INNER JOIN quizzes ON quizzes.id = questions.quiz_id
WHERE quizzes.id = :id
GROUP BY fulfillments.id
EOL;

        $database = DB::getInstance();
        return $database->selectMany($query, ["id" => $id], Fulfillment::class);
    }
}