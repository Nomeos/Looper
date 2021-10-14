<?php
namespace App\models;

use Thynkon\SimpleOrm\Model;

class Answer extends Model
{
    public static string $table = "answers";
    protected string $primaryKey = "id";
    public int $id;
    public string $value;
    public int $question_id;
    public string $fullfillment_id;
}
