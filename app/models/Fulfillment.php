<?php
namespace App\models;

use Thynkon\SimpleOrm\database\DB;
use Thynkon\SimpleOrm\Model;

class Fulfillment extends Model
{
    public static string $table = "fulfillments";
    protected string $primaryKey = "id";
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
}