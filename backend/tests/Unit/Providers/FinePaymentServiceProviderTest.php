<?php

namespace Tests\Unit\Providers;

use Tests\TestCase;
use App\Contracts\Services\DarajaPaymentServiceInterface;

class FinePaymentServiceProviderTest extends TestCase
{
    public function test_it_resolves_daraja_payment_service_interface(): void
    {
        $service = $this->app->make(DarajaPaymentServiceInterface::class);
        $this->assertInstanceOf(DarajaPaymentServiceInterface::class, $service);
    }
}
