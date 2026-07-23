<?php

namespace App\Contracts\Services;

interface OpenAiRecommendationServiceInterface
{
    public function generateBookRecommendations(string $prompt, array $userHistory = []): array;

    public function chat(string $message, array $conversationContext = []): string;
}
