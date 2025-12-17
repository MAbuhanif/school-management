<?php

use App\Models\User;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Verified;

// Bootstrap Laravel
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// 1. Create a User
echo "Creating User...\n";
$user = User::factory()->create([
    'email_verified_at' => null,
    'password' => bcrypt('password'),
]);
echo "User created: ID {$user->id}, Email: {$user->email}\n";
echo "Initial Verified Status: " . ($user->hasVerifiedEmail() ? 'YES' : 'NO') . "\n";

// 2. Generate Verification URL
$verificationUrl = URL::temporarySignedRoute(
    'verification.verify',
    now()->addMinutes(60),
    ['id' => $user->id, 'hash' => sha1($user->getEmailForVerification())]
);
echo "Verification URL generated: {$verificationUrl}\n";

// 3. Simulate Request to Verify
// We can't easily simulate a full HTTP request here without HTTP tests, 
// so we will manually invoke the logic from VerifyEmailController.
echo "Simulating Verification Logic...\n";

// Mocking logic from VerifyEmailController
if (! $user->hasVerifiedEmail()) {
    if ($user->markEmailAsVerified()) {
        event(new Verified($user));
        echo "markEmailAsVerified() returned TRUE.\n";
    } else {
        echo "markEmailAsVerified() returned FALSE.\n";
    }
} else {
    echo "User was already verified (unexpected).\n";
}

// 4. Check DB Persistence
$user->refresh();
echo "Post-Verification DB Status:\n";
echo "email_verified_at: " . $user->email_verified_at . "\n";
echo "hasVerifiedEmail(): " . ($user->hasVerifiedEmail() ? 'YES' : 'NO') . "\n";

// 5. Check if another instance sees it
$freshUser = User::find($user->id);
echo "Fresh User Instance Status: " . ($freshUser->hasVerifiedEmail() ? 'YES' : 'NO') . "\n";

