<?php

require __DIR__ . '/vendor/autoload.php';

use Silex\Application;
use CodeExperts\App\User\User;

$app = new Application();

$app->get('/', function(){
    new User();
    return "hello WOrld";
});

$app->run();