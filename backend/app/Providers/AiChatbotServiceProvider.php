<?php

namespace App\Providers;

use App\Services\OpenAiRecommendationService;
use Illuminate\Support\ServiceProvider;

class AiChatbotServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(OpenAiRecommendationService::class, function () {
            return new OpenAiRecommendationService();
        });
    }

    public function boot(): void
    {
        // Secret API credentials injection into container
    }
}
