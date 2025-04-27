<?php

namespace App\Services\Logger;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class LoggerService {
    private Logger $logger;
    private StreamHandler $handler;

    public function __construct() {
        $this->logger = new Logger('app');
        $this->handler = new StreamHandler(__DIR__ . '/../../../log/app.log', Logger::DEBUG);
        $this->logger->pushHandler($this->handler);
    }

    /**
     * Cambia il file di log di default
     * @param string $filename
     * @return $this
     */
    public function setLogFile(string $filename): static
    {
        $logPath = __DIR__ . "/../../../log/{$filename}";
        $this->logger->popHandler();
        $this->handler = new StreamHandler($logPath, Logger::DEBUG);
        $this->logger->pushHandler($this->handler);

        return $this;

    }

    /**
     * Ottieni l'istanza del logger
     * @return Logger
     */
    public function getLogger(): Logger
    {
        return $this->logger;
    }

}