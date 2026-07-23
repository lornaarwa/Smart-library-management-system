<?php

namespace App\Providers;

use App\Contracts\Services\OpenAiRecommendationServiceInterface;
use App\Services\OpenAiRecommendationService;
use Illuminate\Support\ServiceProvider;

class AiChatbotServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(OpenAiRecommendationServiceInterface::class, OpenAiRecommendationService::class);
        $this->app->singleton(OpenAiRecommendationService::class, OpenAiRecommendationService::class);
    }

    public function boot(): void
    {
        // Secret API credentials injection into container
    }
}
