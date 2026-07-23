<?php

namespace Tests\Unit\Providers;

use Tests\TestCase;
use App\Contracts\Services\NotificationDispatcherServiceInterface;

class LibraryEventServiceProviderTest extends TestCase
{
    public function test_it_resolves_notification_dispatcher_service_interface(): void
    {
        $service = $this->app->make(NotificationDispatcherServiceInterface::class);
        $this->assertInstanceOf(NotificationDispatcherServiceInterface::class, $service);
    }
}
