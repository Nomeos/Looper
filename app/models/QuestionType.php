<?php
namespace App\models;

use Thynkon\SimpleOrm\Model;

class QuestionType extends Model
{
    public static string $table = "question_types";
    protected string $primaryKey = "id";
    public int $id;
    public string $label;
}