<?php
namespace App\models;

use Thynkon\SimpleOrm\Model;

class Question extends Model
{
    public static string $table = "questions";
    protected string $primaryKey = "id";
    public int $id;
    public string $label;
    public int $question_type_id;
    public int $quiz_id;
}