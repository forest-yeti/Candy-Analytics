<?php

namespace App\CandyAnalytics\Request\Service;

interface RequestDecoratorInterface
{
    public function getAuthorizationBearerHeader(): string;
}
