<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\App;
use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    public function test_cookies_are_secure_in_production()
    {
        // Mock production environment
        App::detectEnvironment(fn() => 'production');

        $response = $this->get('/');

        $cookies = $response->headers->getCookies();
        
        $foundSession = false;
        foreach ($cookies as $cookie) {
            if (str_contains($cookie->getName(), '_session')) {
                $foundSession = true;
                $this->assertTrue($cookie->isSecure(), 'Session cookie is not Secure');
                $this->assertTrue($cookie->isHttpOnly(), 'Session cookie is not HttpOnly');
                $this->assertEquals('strict', strtolower($cookie->getSameSite()), 'Session cookie SameSite is not Strict');
            }
            if (str_contains($cookie->getName(), 'XSRF-TOKEN')) {
                // XSRF token is often not HttpOnly so JS can read it, but should be Secure in prod
                $this->assertTrue($cookie->isSecure(), 'XSRF cookie is not Secure');
            }
        }
        
        $this->assertTrue($foundSession, 'Session cookie not found');
    }
}
