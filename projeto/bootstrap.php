<?php
define('DS', DIRECTORY_SEPARATOR);
define('HOME', 'http://codestart.com.br/');
define('CONFIG_PATH', __DIR__ . '/config/');
define('VIEWS_FOLDER', __DIR__ . '/views/');
define('UPLOAD_FOLDER', __DIR__ . '/public/assets/uploads/');

date_default_timezone_set("America/Sao_Paulo");

require __DIR__ . DS . 'vendor' . DS . 'autoload.php';

putenv('PAGSEGURO_TOKEN_SANDBOX=74AC9F13254844E592C46F81A546A41B');
putenv('PAGSEGURO_EMAIL=nandokstro@gmail.com');
putenv('PAGSEGURO_ENV=sandbox');
