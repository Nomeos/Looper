<?php
namespace App\models;

use Thynkon\SimpleOrm\Model;
use Thynkon\SimpleOrm\database\DB;

class QuizState extends Model
{
    public static string $table = "quiz_states";
    protected string $primaryKey = "id";
    public int $id;
    public string $label;

    public function quizzes()
    {
        $query = <<< EOL
SELECT *
FROM quizzes
WHERE quiz_state_id = :id;
EOL;

        $database = DB::getInstance();
        return $database->selectMany($query, ["id" => $this->id], Quiz::class);
    }
}