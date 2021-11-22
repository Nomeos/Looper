<?php

namespace App\lib\http;

class CsrfToken
{
    public const LENGTH = 32;

    public static function generate()
    {
        return bin2hex(random_bytes(static::LENGTH));
    }
}