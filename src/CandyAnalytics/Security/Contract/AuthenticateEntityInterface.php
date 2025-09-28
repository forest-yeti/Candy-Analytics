<?php

namespace App\CandyAnalytics\Security\Contract;

interface AuthenticateEntityInterface
{
    public function getIdentifier(): int;
}
