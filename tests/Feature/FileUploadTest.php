<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class FileUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_upload_file()
    {
        Storage::fake('local');
        $user = User::factory()->create();

        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->actingAs($user)->postJson('/api/v1/uploads', [
            'file' => $file,
            'disk' => 'local',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['path', 'url']);

        Storage::disk('local')->assertExists($response->json('path'));
    }

    public function test_can_access_file_with_signed_url()
    {
        Storage::fake('local');
        $user = User::factory()->create();

        $file = UploadedFile::fake()->image('report.pdf');
        $path = $file->store('uploads', 'local');

        $url = URL::temporarySignedRoute(
            'files.access',
            now()->addMinutes(60),
            ['path' => $path, 'disk' => 'local']
        );

        $response = $this->actingAs($user)->get($url);

        $response->assertStatus(200);
    }

    public function test_cannot_access_file_with_invalid_signature()
    {
        $user = User::factory()->create();
        $url = route('files.access', ['path' => 'uploads/test.jpg', 'disk' => 'local']);

        $response = $this->actingAs($user)->get($url);

        $response->assertStatus(403);
    }
}
