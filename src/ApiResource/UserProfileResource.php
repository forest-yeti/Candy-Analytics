<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\CandyAnalytics\User\Contract\UserProfileDataInterface;
use App\Entity\User;
use App\State\UserProcessor;
use App\State\UserProvider;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/v1/users',
            normalizationContext: ['groups' => ['user:read']],
            denormalizationContext: ['groups' => ['user:write']],
            output: User::class,
            name: UserProcessor::REGISTRATION,
            processor: UserProcessor::class
        ),
        new Put(
            uriTemplate: '/v1/users',
            normalizationContext: ['groups' => ['user:read']],
            denormalizationContext: ['groups' => ['user:write']],
            output: User::class,
            name: UserProcessor::LOGIN,
            processor: UserProcessor::class
        ),
        new Get(
            uriTemplate: '/v1/profile',
            normalizationContext: ['groups' => ['user:read']],
            output: User::class,
            provider: UserProvider::class,
        )
    ]
)]
class UserProfileResource implements UserProfileDataInterface
{
    #[Groups(['user:write'])]
    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $email;

    #[Groups(['user:write'])]
    #[Assert\NotBlank]
    #[Assert\Length(min: 8, max: 256)]
    private ?string $password;

    public function getEmail(): string
    {
        return $this->email ?? '';
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password ?? '';
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }
}
