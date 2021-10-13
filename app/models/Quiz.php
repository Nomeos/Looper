<?php
namespace App\models;

use Thynkon\SimpleOrm\database\DB;
use Thynkon\SimpleOrm\Model;

class Quiz extends Model
{
    static protected string $table = "quiz";
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
SELECT quiz_state.*
FROM quiz
INNER JOIN quiz_state ON quiz_state.id = quiz.quiz_state_id
WHERE quiz.id = :id;
EOL;

        $database = DB::getInstance();
        return $database->selectOne($query, ["id" => $this->id], QuizState::class);
    }
}