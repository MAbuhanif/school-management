<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class SecurityConfigTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_rate_limiting()
    {
        RateLimiter::clear('api');

        // Make 60 requests (allowed)
        for ($i = 0; $i < 60; $i++) {
            $this->getJson('/api/v1/courses')->assertStatus(200); // Assuming this is a public GET for now, or returns 401 if auth required but not 429
        }

        // 61st request should fail
        $this->getJson('/api/v1/courses')->assertStatus(429);
    }

    public function test_cors_headers_allowed_origin()
    {
        Config::set('cors.allowed_origins', ['http://localhost:3000']);

        $response = $this->optionsJson('/api/v1/courses', [], [
            'Origin' => 'http://localhost:3000',
            'Access-Control-Request-Method' => 'GET',
        ]);

        $response->assertStatus(204);
        $response->assertHeader('Access-Control-Allow-Origin', 'http://localhost:3000');
        $response->assertHeader('Access-Control-Allow-Methods');
    }

    public function test_cors_headers_disallowed_origin()
    {
        Config::set('cors.allowed_origins', ['http://localhost:3000']);

        $response = $this->optionsJson('/api/v1/courses', [], [
            'Origin' => 'http://evil.com',
            'Access-Control-Request-Method' => 'GET',
        ]);

        // Laravel CORS middleware usually returns 200 or 204 but WITHOUT the Access-Control-Allow-Origin header if disallowed,
        // OR it doesn't handle passing it through. 
        // Actually, if origin is not allowed, no CORS headers are sent.
        
        if ($response->headers->has('Access-Control-Allow-Origin')) {
             $this->assertNotEquals('http://evil.com', $response->headers->get('Access-Control-Allow-Origin'));
        } else {
             $response->assertHeaderMissing('Access-Control-Allow-Origin');
        }
    }
}
