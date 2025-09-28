<?php

namespace App\CandyAnalytics\User\UseCase;

use App\CandyAnalytics\Security\Service\GuardSecurityInterface;
use App\CandyAnalytics\User\Contract\UserProfileDataInterface;
use App\CandyAnalytics\User\Exception\UserException;
use App\Helper\PasswordHelper;
use App\Repository\UserRepository;

readonly class UserLoginCase
{
    public function __construct(
        private UserRepository $userRepository,
        private GuardSecurityInterface $guardSecurityService
    ) {}

    /**
     * @throws UserException
     */
    public function do(UserProfileDataInterface $data): string
    {
        $user = $this->userRepository->findByEmail($data->getEmail());
        if ($user === null) {
            throw new UserException('Пользователь не найден');
        }

        if (!PasswordHelper::verifyHash($data->getPassword(), $user->getPassword())) {
            throw new UserException('Пользователь не найден');
        }

        return $this->guardSecurityService->authenticate($user);
    }
}
