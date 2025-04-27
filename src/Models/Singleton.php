<?php

namespace App\Models;

use PDO;

class Singleton
{

    private static ?Singleton $instance = null;

    private PDO $conn;

    private function __construct()
    {
        $this->conn = new PDO("mysql:host={$_ENV['DB_HOST']}; dbname={$_ENV['DB_DATABASE']}",$_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    protected static function getInstance(): ?Singleton
    {
        if(self::$instance === null) {
            self::$instance = new Singleton();
        }

        return self::$instance;
    }

    protected function getConnection(): PDO {
        return $this->conn;
    }

    public static function db(): PDO
    {
        return self::getInstance()->getConnection();
    }

}