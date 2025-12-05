<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SecureFileUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_upload_file_privately()
    {
        Storage::fake('local');
        $user = User::factory()->create();

        $file = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

        $response = $this->actingAs($user)->postJson('/api/v1/uploads', [
            'file' => $file,
            'directory' => 'documents',
        ]);

        $response->assertCreated();
        $response->assertJsonStructure(['path', 'url']);

        $path = $response->json('path');
        Storage::disk('local')->assertExists($path);
        
        // Ensure it is NOT in public disk (implicit check as we faked 'local' which is private root)
        // If we faked 'public', we could check it doesn't exist there, but 'local' is the default private one.
    }

    public function test_upload_rejects_dangerous_files()
    {
        $user = User::factory()->create();

        $file = UploadedFile::fake()->create('script.php', 100, 'application/x-php');

        $response = $this->actingAs($user)->postJson('/api/v1/uploads', [
            'file' => $file,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['file']);
    }

    public function test_access_requires_valid_signature()
    {
        Storage::fake('local');
        $user = User::factory()->create();
        $file = UploadedFile::fake()->create('image.jpg', 100, 'image/jpeg');

        // Upload
        $uploadResponse = $this->actingAs($user)->postJson('/api/v1/uploads', [
            'file' => $file,
            'disk' => 'public', // Should be ignored and forced to local
        ]);
        
        $signedUrl = $uploadResponse->json('url');
        $path = $uploadResponse->json('path');

        // Access with valid signature
        $this->get($signedUrl)->assertOk();

        // Access with invalid signature
        $invalidUrl = str_replace('signature=', 'signature=invalid', $signedUrl);
        $this->get($invalidUrl)->assertStatus(403);
    }
}
