<?php

namespace Tests\Unit\Middleware;

use Tests\TestCase;
use App\Models\Member;
use App\Models\User;
use App\Http\Middleware\CheckBannedStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBannedStatusTest extends TestCase
{
    use RefreshDatabase;

    protected CheckBannedStatus $middleware;

    protected function setUp(): void
    {
        parent::setUp();
        $this->middleware = new CheckBannedStatus();
    }

    public function test_it_blocks_suspended_members(): void
    {
        $user = User::create(['name' => 'Banned User', 'email' => 'banned@example.com', 'password' => 'secret']);
        Member::create([
            'user_id' => $user->id,
            'member_number' => 'MEM-9999',
            'is_banned' => true,
            'ban_reason' => 'Overdue fines exceed limit',
        ]);

        $request = Request::create('/api/v1/loans', 'GET');
        $request->setUserResolver(fn () => $user);

        $response = $this->middleware->handle($request, fn () => new Response());
        $this->assertEquals(403, $response->getStatusCode());
    }
}
