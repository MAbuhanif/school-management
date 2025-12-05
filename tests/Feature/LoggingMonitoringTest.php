<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class LoggingMonitoringTest extends TestCase
{
    public function test_logging_channel_is_daily_stack()
    {
        $defaultChannel = config('logging.default');
        $stackChannels = config('logging.channels.stack.channels');

        $this->assertEquals('stack', $defaultChannel);
        $this->assertContains('daily', $stackChannels);
    }
    
    public function test_sentry_service_provider_is_registered()
    {
        $providers = app()->getLoadedProviders();
        $this->assertArrayHasKey('Sentry\Laravel\ServiceProvider', $providers);
    }
}
