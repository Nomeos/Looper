<?php
namespace App\models;

use Thynkon\SimpleOrm\database\DB;
use Thynkon\SimpleOrm\Model;

class QuestionType extends Model
{
    public static string $table = "question_types";
    protected string $primaryKey = "id";
    public int $id;
    public string $label;

    public function questions()
    {
        $query = <<< EOL
SELECT *
FROM questions
WHERE question_type_id = :id;
EOL;

        $database = DB::getInstance();
        return $database->selectMany($query, ["id" => $this->id], Question::class);
    }
}