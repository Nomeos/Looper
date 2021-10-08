<?php

require_once("app/lib/Http/Session.php");

class SessionFactory
{
    public static function fromCookie($cookie)
    {
        return new Session($cookie["PHPSESSID"]);
    }
}