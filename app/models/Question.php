<?php
namespace App\models;

use Thynkon\SimpleOrm\database\DB;
use Thynkon\SimpleOrm\Model;

class Question extends Model
{
    public static string $table = "questions";
    protected string $primaryKey = "id";
    public int $id;
    public string $label;
    public int $question_type_id;
    public int $quiz_id;

    public function type()
    {
        $query = <<< EOL
SELECT question_types.*
FROM questions
INNER JOIN question_types ON question_types.id = questions.question_type_id
WHERE questions.id = :id;
EOL;

        $database = DB::getInstance();
        return $database->selectOne($query, ["id" => $this->id], QuestionType::class);
    }

    public function quiz()
    {
        $query = <<< EOL
SELECT *
FROM questions
INNER JOIN quizzes ON quizzes.id = questions.quiz_id
WHERE questions.id = :id;
EOL;

        $database = DB::getInstance();
        return $database->selectOne($query, ["id" => $this->id], Quiz::class);
    }
}