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
}