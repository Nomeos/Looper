<?php

require_once("app/lib/http/Session.php");

class SessionFactory
{
    public static function fromCookie(array $cookie): Session
    {
        return new Session($cookie["PHPSESSID"]);
    }
}
