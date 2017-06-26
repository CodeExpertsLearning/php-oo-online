<?php
require __DIR__ . '/../bootstrap.php';

$url = isset($_SERVER['REQUEST_URI'])? explode('/',  substr($_SERVER['REQUEST_URI'], 1)) : '';

if($url[0] == '')
    $url[0] = 'home';

$controller = 'CodeExperts\App\Controller\\' . ucfirst($url[0]) .'Controller';

if($url[0] == 'admin') {
    $controller = 'CodeExperts\App\Controller\Admin\\' . ucfirst($url[1]) .'Controller';
}

if(class_exists($controller)){

    if($url[0] == 'admin'){
        $actions = isset($url[2]) ? $url[2] : 'index';
        $params  = isset($url[3]) ? $url[3] : '';
    } else {
        $actions = isset($url[1]) ? $url[1] : 'index';
        $params  = isset($url[2]) ? $url[2] : '';
    }

    $response = call_user_func_array([new $controller, $actions], [$params]);

    print $response;
} else {
    print 'Controller não informado';
}