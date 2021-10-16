<?php
namespace App\models;

use Thynkon\SimpleOrm\database\DB;
use Thynkon\SimpleOrm\Model;

class Answer extends Model
{
    public static string $table = "answers";
    protected string $primaryKey = "id";
    public int $id;
    public string $value;
    public int $question_id;
    public string $fulfillment_id;

    public function fulfillment()
    {
        $query = <<< EOL
SELECT *
FROM answers
INNER JOIN fulfillments ON fulfillments.id = answers.fulfillment_id
WHERE answers.id = :id;
EOL;

        $database = DB::getInstance();
        return $database->selectOne($query, ["id" => $this->id], Fulfillment::class);
    }
}
