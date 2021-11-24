<?php

namespace App\models;

use Thynkon\SimpleOrm\database\DB;
use Thynkon\SimpleOrm\Model;

class Fulfillment extends Model
{
    public static string $table = "fulfillments";
    protected string $primaryKey = "id";
    public int $id;
    public string $date;

    public function answers()
    {
        $query = <<< EOL
SELECT *
FROM answers
WHERE fulfillment_id = :id;
EOL;

        $database = DB::getInstance();
        return $database->selectMany($query, ["id" => $this->id], Answer::class);
    }
    
    public function quiz(int $id)
    {
        $query = <<< EOL
SELECT *
FROM answers
INNER JOIN fulfillments ON fulfillments.id = answers.fulfillment_id
INNER JOIN questions ON questions.id = answers.question_id
INNER JOIN quizzes ON quizzes.id = questions.quiz_id
WHERE answers.fulfillment_id = :id;
EOL;

        $database = DB::getInstance();
        return $database->selectOne($query, ["id" => $id], Answer::class);
    }

    public function questions()
    {
        $query = <<< EOL
SELECT questions.*
FROM fulfillments
INNER JOIN answers ON answers.fulfillment_id = fulfillments.id
INNER JOIN questions ON questions.id = answers.question_id
WHERE fulfillments.id = :id;
EOL;

        $database = DB::getInstance();
        return $database->selectMany($query, ["id" => $this->id], Question::class);
    }

}