<?php
/** @var App $app */

use App\Controllers\HomeController;
use App\Controllers\TestController;
use Slim\App;

$app->get('/', HomeController::class . ':index')->setName('home');
$app->get('/test', TestController::class . ':index');
