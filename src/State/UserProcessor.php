<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\UserProfileResource;
use App\CandyAnalytics\User\Exception\UserException;
use App\CandyAnalytics\User\UseCase\UserLoginCase;
use App\CandyAnalytics\User\UseCase\UserRegistrationCase;
use App\Entity\User;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

readonly class UserProcessor implements ProcessorInterface
{
    public const string REGISTRATION = 'REGISTRATION';
    public const string LOGIN = 'LOGIN';

    public function __construct(
        private UserRegistrationCase $userRegistrationCase,
        private UserLoginCase $userLoginCase
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): User|string
    {
        return match ($operation->getName()) {
            self::REGISTRATION => $this->registration($data),
            self::LOGIN => $this->login($data),
            default => throw new RuntimeException("Неизвестная операция [{$operation->getName()}]"),
        };
    }

    private function registration(UserProfileResource $resource): User
    {
        try {
            return $this->userRegistrationCase->do($resource);
        } catch (UserException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }

    private function login(UserProfileResource $resource): string
    {
        try {
            return $this->userLoginCase->do($resource);
        } catch (UserException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }
}
