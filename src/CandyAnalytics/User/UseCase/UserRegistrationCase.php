<?php

namespace App\CandyAnalytics\User\UseCase;

use App\CandyAnalytics\User\Contract\UserProfileDataInterface;
use App\CandyAnalytics\User\Exception\UserException;
use App\Entity\User;
use App\Helper\PasswordHelper;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

readonly class UserRegistrationCase
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager
    ) {}

    /**
     * @throws UserException
     */
    public function do(UserProfileDataInterface $data): User
    {
        $user = $this->userRepository->findByEmail($data->getEmail());
        if (null !== $user) {
            throw new UserException('Данная почта уже занята, попробуйте другую');
        }

        $user = (new User())
            ->setEmail($data->getEmail())
            ->setPassword(PasswordHelper::createHash($data->getPassword()));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}
