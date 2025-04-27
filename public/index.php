<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

use DI\Container;
use Dotenv\Dotenv;
use Dotenv\Exception\InvalidFileException;
use Dotenv\Exception\InvalidPathException;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

// Carica le variabili di ambiente dal file .env
try {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
} catch (InvalidPathException|InvalidFileException $e) {
}

// Create Container using PHP-DI
$container = new Container();
AppFactory::setContainer($container);

$app = AppFactory::create();

require __DIR__ . '/../config/dependencies.php';
require __DIR__ . '/../config/middleware.php';
require __DIR__ . '/../config/routes.php';

// Avvia l'applicazione
$app->run();