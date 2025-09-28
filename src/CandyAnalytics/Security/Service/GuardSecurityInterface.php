<?php

namespace App\CandyAnalytics\Security\Service;

use App\CandyAnalytics\Security\Contract\AuthenticateEntityInterface;
use App\CandyAnalytics\Security\Exception\AuthenticateException;
use App\Entity\User;

interface GuardSecurityInterface
{
    public function authenticate(AuthenticateEntityInterface $authenticateEntity): string;

    /**
     * @throws AuthenticateException
     */
    public function getAuthenticateEntity(): User;
}
