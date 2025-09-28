<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\CandyAnalytics\Security\Exception\AuthenticateException;
use App\CandyAnalytics\Security\Service\GuardSecurityInterface;

readonly class UserProvider implements ProviderInterface
{
    public function __construct(
        private GuardSecurityInterface $guardSecurityService
    ) {}

    /**
     * @throws AuthenticateException
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        return $this->guardSecurityService->getAuthenticateEntity();
    }
}
