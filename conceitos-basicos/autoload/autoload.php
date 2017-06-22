<?php

//require __DIR__ . '/class/User.php';

function __autoload($class) {
    require __DIR__ . '/class/' . $class . '.php';
}

$user = new User();