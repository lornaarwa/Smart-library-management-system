<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Contracts\Services\AuthSessionServiceInterface;
use App\Contracts\Services\CatalogSearchEngineInterface;
use App\Contracts\Services\BookAvailabilityServiceInterface;
use App\Contracts\Services\BorrowLimitServiceInterface;
use App\Contracts\Services\QueueReservationServiceInterface;
use App\Contracts\Services\DarajaPaymentServiceInterface;
use App\Contracts\Services\NotificationDispatcherServiceInterface;
use App\Contracts\Services\OpenAiRecommendationServiceInterface;
use App\Contracts\Services\TokenBucketRateLimiterInterface;

class ContractsIntegrityTest extends TestCase
{
    public function test_all_service_interfaces_exist_and_are_interfaces(): void
    {
        $contracts = [
            AuthSessionServiceInterface::class,
            CatalogSearchEngineInterface::class,
            BookAvailabilityServiceInterface::class,
            BorrowLimitServiceInterface::class,
            QueueReservationServiceInterface::class,
            DarajaPaymentServiceInterface::class,
            NotificationDispatcherServiceInterface::class,
            OpenAiRecommendationServiceInterface::class,
            TokenBucketRateLimiterInterface::class,
        ];

        foreach ($contracts as $contract) {
            $this->assertTrue(
                interface_exists($contract),
                "Interface {$contract} does not exist."
            );
        }
    }
}
