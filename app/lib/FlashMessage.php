<?php

namespace App\lib;

class FlashMessage
{
    public const OK = 0;
    public const ERROR = 1;

    public static function get()
    {
        $message = "";

        if (isset($_SESSION["flash_message"])) {
            $message = $_SESSION["flash_message"];
            unset($_SESSION["flash_message"]);
        }

        return $message;
    }

    private static function flash(int $type, string $message)
    {
        $_SESSION["flash_message"]["type"] = $type;
        $_SESSION["flash_message"]["value"] = $message;
    }

    public static function success($message)
    {
        static::flash(static::OK, $message);
    }

    public static function error($message)
    {
        static::flash(static::ERROR, $message);
    }
}