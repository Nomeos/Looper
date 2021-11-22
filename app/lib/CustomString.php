<?php

namespace App\lib;

class CustomString
{
    static public function sanitize($input)
    {
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }
}