<?php
header("Access-Control-Allow-Origin: *");
define('APP__ROOT', getcwd() ?: __DIR__ . '/../..');

require(APP__ROOT . '/vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable(APP__ROOT . '/');
$dotenv->safeLoad();

$app = \extas\components\api\App::create();
$app->run();
