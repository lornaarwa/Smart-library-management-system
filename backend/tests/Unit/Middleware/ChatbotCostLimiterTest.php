<?php

namespace Tests\Unit\Middleware;

use Tests\TestCase;
use App\Models\AiUsageLog;
use App\Models\Member;
use App\Models\User;
use App\Http\Middleware\ChatbotCostLimiter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ChatbotCostLimiterTest extends TestCase
{
    use RefreshDatabase;

    protected ChatbotCostLimiter $middleware;

    protected function setUp(): void
    {
        parent::setUp();
        $this->middleware = new ChatbotCostLimiter();
    }

    public function test_it_blocks_ai_requests_when_daily_quota_exceeded(): void
    {
        $user = User::create(['name' => 'AI User', 'email' => 'ai@example.com', 'password' => 'secret']);
        $member = Member::create(['user_id' => $user->id, 'member_number' => 'MEM-4444']);

        AiUsageLog::create([
            'member_id' => $member->id,
            'tokens_consumed' => 25000,
            'cost_estimate' => 0.05,
            'request_type' => 'chat',
        ]);

        $request = Request::create('/api/v1/ai/chat', 'POST');
        $request->setUserResolver(fn () => $user);

        $response = $this->middleware->handle($request, fn () => new Response());
        $this->assertEquals(429, $response->getStatusCode());
    }
}
