<?php

namespace App\lib\http;

use App\lib\http\Session;

class SessionFactory
{
    public static function fromCookie(array $cookie): Session
    {
        return new Session($cookie["PHPSESSID"]);
    }
}
