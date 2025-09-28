<?php

namespace App\Helper;

class PasswordHelper
{
    public static function createHash(string $originalPassword): string
    {
        return password_hash($originalPassword, PASSWORD_DEFAULT);
    }

    public static function verifyHash(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}
