<?php

class Email
{
    static public $to;

    static public function send()
    {
        return "Email sended to " . self::$to;
    }
}

Email::$to = "nandokstro@gmail.com";

print Email::send();
