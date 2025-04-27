<?php

use App\Controllers\HomeController;
use App\Controllers\TestController;
use App\Services\Logger\LoggerService;
use App\Services\Mailer\Message;
use DI\Container;
use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Views\Twig;
use Symfony\Component\Mailer\Transport;

/**
 * @var Container $container
 * @var App $app
 */

$container = $app->getContainer();

$container->set('settings', function(ContainerInterface $container) {

    $settings = require __DIR__ . '/settings.php';
    return $settings['settings'];

});

// Monolog
$container->set('loggerService', function(ContainerInterface $container) {
    return new LoggerService();
});

// Twig
$container->set('view', function () {
    return Twig::create(__DIR__ . '/../src/Views', [
        'cache' => false, // Imposta un percorso di cache per la produzione
    ]);
});

// Symfony Mailer
$container->set('mailer', function(ContainerInterface $container) {

    $config = $container->get('settings')['mail'];

    // Create the Transport
    $dns = 'smtp://' . urlencode($config['username']) . ':' . urlencode($config['password']) . '@' . $config['host'] . ':' . $config['port'];
    $transport = Transport::fromDsn($dns);

    // Create the Mailer using your created Transport
    return new Symfony\Component\Mailer\Mailer($transport);

});

// Message
$container->set('message', function(ContainerInterface $container) {
    return new Message($container);
});

// Register Controllers
$container->set(HomeController::class, function (ContainerInterface $container) {
    return new HomeController($container);
});
$container->set(TestController::class, function (ContainerInterface $container) {
    return new TestController($container);
});