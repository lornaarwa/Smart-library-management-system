<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\User;
use App\Services\NotificationDispatcherService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotificationDispatcherServiceTest extends TestCase
{
    use RefreshDatabase;

    protected NotificationDispatcherService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new NotificationDispatcherService();
    }

    public function test_it_dispatches_notifications_successfully(): void
    {
        $user = User::create([
            'name' => 'Notify User',
            'email' => 'notify@example.com',
            'password' => bcrypt('password123'),
        ]);

        $this->assertTrue($this->service->sendOverdueNotification($user, ['title' => 'Clean Code']));
        $this->assertTrue($this->service->sendReservationAvailableNotification($user, ['title' => 'Design Patterns']));
        $this->assertTrue($this->service->sendFineReceiptNotification($user, ['receipt' => 'MPESA123', 'amount' => 50]));
    }
}
