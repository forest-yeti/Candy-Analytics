<?php

namespace App\Infrastructure\HttpRequest;

use App\CandyAnalytics\Request\Service\RequestDecoratorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class HttpRequestDecorator implements RequestDecoratorInterface
{
    private ?Request $request = null;

    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
    }

    public function getAuthorizationBearerHeader(): string
    {
        if (null === $this->request) {
            return '';
        }

        $authorizationHeader = $this->request->headers->get('Auth');
        if (!str_starts_with($authorizationHeader, 'Bearer ')) {
            return '';
        }

        return str_replace('Bearer ', '', $authorizationHeader);
    }
}
