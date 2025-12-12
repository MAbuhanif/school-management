<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Role;

class NavigationTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_access_routes()
    {
        $this->seed(\Database\Seeders\RoleSeeder::class);
        $user = User::where('email', 'superadmin@school.com')->first();
        
        $routes = [
            'dashboard',
            'students.index',
            'teachers.index',
            'courses.index',
            'enrollments.index',
            'grades.index',
            'reports.index',
            'settings.index',
        ];

        foreach ($routes as $route) {
            $response = $this->actingAs($user)->get(route($route));
            $response->assertStatus(200);
        }
    }
}
