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
    public string $slug;

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

    public static function defaultState()
    {
        $query = <<< EOL
SELECT *
FROM quiz_states
WHERE slug = :slug;
EOL;

        $database = DB::getInstance();
        return $database->selectOne($query, ["slug" => "BUILD"], QuizState::class);
    }

    public function next()
    {
        $next_state = null;
        $slug = null;

        switch ($this->slug) {
            case "BUILD":
                $slug = "ANSW";
                break;

            case "ANSW":
                $slug = "CLOS";
                break;

            case "CLOS":
                // closed is the last state
                break;

            default:
                break;
        }

        $next_state = QuizState::where("slug", $slug)[0];
        return $next_state;
    }

}