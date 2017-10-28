<?php
define('DS', DIRECTORY_SEPARATOR);
define('HOME', 'http://localhost:3030/');
define('CONFIG_PATH', __DIR__ . '/config/');
define('VIEWS_FOLDER', __DIR__ . '/views/');
define('UPLOAD_FOLDER', __DIR__ . '/public/assets/uploads/');

date_default_timezone_set("America/Sao_Paulo");

require __DIR__ . DS . 'vendor' . DS . 'autoload.php';

putenv('PAGSEGURO_TOKEN_SANDBOX=');
putenv('PAGSEGURO_EMAIL=');
putenv('PAGSEGURO_ENV=sandbox');