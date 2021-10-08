<?php
namespace App\models;

use Thynkon\SimpleOrm\Model;

class QuizState extends Model
{
    static protected string $table = "quiz_state";
    protected string $primaryKey = "id";
    public int $id;
    public string $label;
}