<?php
/** @var App $app */

use Slim\App;
use Slim\Views\TwigMiddleware;

// Add routing Middleware
$app->addRoutingMiddleware();

// Add error handling middleware.
$app->addErrorMiddleware((bool) $_ENV['APP_DISPLAY_ERRORS'], true, false);

// Add Twig-View Middleware
$app->add(TwigMiddleware::createFromContainer($app));
