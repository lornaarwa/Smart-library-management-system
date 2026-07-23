<?php

namespace App\Contracts\Services;

use App\Models\Fine;

interface DarajaPaymentServiceInterface
{
    public function getAccessToken(): string;

    public function initiateStkPush(Fine $fine, string $phoneNumber, float $amount): array;

    public function processCallback(array $callbackData): array;
}
