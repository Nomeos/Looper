<?php
namespace App\models;

use Thynkon\SimpleOrm\Model;

class Fullfillment extends Model
{
    public static string $table = "fullfillments";
    protected string $primaryKey = "id";
    public string $date;
}