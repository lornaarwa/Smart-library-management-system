<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\OpenAiRecommendationService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OpenAiRecommendationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected OpenAiRecommendationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new OpenAiRecommendationService();
    }

    public function test_it_generates_recommendations_and_chat_responses(): void
    {
        $res = $this->service->generateBookRecommendations('Software Architecture');
        $this->assertIsArray($res);
        $this->assertArrayHasKey('recommendations', $res);

        $chatResponse = $this->service->chat('What books should I read?');
        $this->assertStringContainsString('SmartLib AI', $chatResponse);
    }
}
