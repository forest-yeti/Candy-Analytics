<?php

namespace App\Infrastructure\Security\Service;

use App\CandyAnalytics\Request\Service\RequestDecoratorInterface;
use App\CandyAnalytics\Security\Contract\AuthenticateEntityInterface;
use App\CandyAnalytics\Security\Exception\AuthenticateException;
use App\CandyAnalytics\Security\Service\GuardSecurityInterface;
use App\Entity\User;
use App\Repository\UserRepository;
use DateTime;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Throwable;

readonly class GuardSecurityService implements GuardSecurityInterface
{
    private const string ALGORITHM = 'HS256';

    public function __construct(
        #[Autowire('%app.secret%')]
        private string $secret,
        #[Autowire('%app.name%')]
        private string $applicationName,
        #[Autowire('%app.security.ttl%')]
        private int $tokenTtl,
        private RequestDecoratorInterface $requestDecorator,
        private UserRepository $userRepository
    ) {}

    /**
     * @throws AuthenticateException
     */
    public function authenticate(AuthenticateEntityInterface $authenticateEntity): string
    {
        try {
            $payload = [
                'iss' => $this->applicationName,
                'aud' => $this->applicationName,
                'exp' => (new DateTime())->modify("+ {$this->tokenTtl} seconds")->getTimestamp(),
                'entity' => [
                    'id' => $authenticateEntity->getIdentifier(),
                ]
            ];
        } catch (Throwable) {
            throw new AuthenticateException();
        }

        return JWT::encode($payload, $this->secret, self::ALGORITHM);
    }

    public function getAuthenticateEntity(): User
    {
        $authorizationHeader = $this->requestDecorator->getAuthorizationBearerHeader();
        if (empty($authorizationHeader)) {
            throw new AuthenticateException();
        }

        try {
            $payload = JWT::decode($authorizationHeader, new Key($this->secret, self::ALGORITHM));
        } catch (Throwable) {
            throw new AuthenticateException();
        }

        $user = $this->userRepository->find($payload->entity->id);
        if (null === $user) {
            throw new AuthenticateException();
        }

        return $user;
    }
}
