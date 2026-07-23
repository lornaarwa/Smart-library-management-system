<?php

namespace Tests\Unit\Providers;

use Tests\TestCase;
use App\Contracts\Services\OpenAiRecommendationServiceInterface;

class AiChatbotServiceProviderTest extends TestCase
{
    public function test_it_resolves_openai_recommendation_service_interface(): void
    {
        $service = $this->app->make(OpenAiRecommendationServiceInterface::class);
        $this->assertInstanceOf(OpenAiRecommendationServiceInterface::class, $service);
    }
}
