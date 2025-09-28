<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\State\PingProvider;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/v1/ping',
            normalizationContext: ['groups' => ['ping:read']],
            provider: PingProvider::class,
        )
    ]
)]
readonly class PingResource
{
    public function __construct(
        #[Groups(['ping:read'])]
        private string $message
    ) {}

    public function getMessage(): string
    {
        return $this->message;
    }
}
