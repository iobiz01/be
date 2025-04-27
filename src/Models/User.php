<?php

namespace App\Models;

class User extends Singleton
{

    public static function getUsers(): array
    {
        $stmt = self::db()->query("SELECT * FROM users");
        return $stmt->fetchAll();
    }

}