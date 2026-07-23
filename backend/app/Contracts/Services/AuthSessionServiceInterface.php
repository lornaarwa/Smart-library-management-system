<?php

namespace App\Contracts\Services;

use App\Models\User;

interface AuthSessionServiceInterface
{
    public function createSessionToken(User $user): string;

    public function validateSessionToken(string $token): ?User;

    public function invalidateSessionToken(string $token): bool;

    public function getAuthenticatedUser(string $token): ?User;
}
