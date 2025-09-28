<?php

namespace App\CandyAnalytics\Security\Exception;

use Exception;

class AuthenticateException extends Exception
{
    public function __construct()
    {
        parent::__construct('Непредвиденная ошибка аутентификации');
    }
}
