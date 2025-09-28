<?php

namespace App\CandyAnalytics\User\Contract;

interface UserProfileDataInterface
{
    public function getEmail(): string;
    public function getPassword(): string;
}