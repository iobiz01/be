<?php

namespace App\Controllers;

use App\Services\Logger\LoggerService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Slim\Views\Twig;

class BaseController
{

    protected Twig $view;
    protected LoggerService $loggerService;
    protected ContainerInterface $container;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->view = $container->get('view');
        $this->loggerService = $container->get('loggerService');
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __get($property) {
        if ($this->container->get($property)) {
            return $this->container->get($property);
        }
        return null;
    }

}