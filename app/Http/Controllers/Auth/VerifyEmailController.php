<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        \Illuminate\Support\Facades\Log::info('VerifyEmailController hit for User ID: ' . $request->user()->id);

        if ($request->user()->hasVerifiedEmail()) {
            \Illuminate\Support\Facades\Log::info('User already verified.');
            return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
        }

        $request->user()->forceFill([
            'email_verified_at' => now(),
        ])->save();
        
        \Illuminate\Support\Facades\Log::info('User verified via forceFill. New status: ' . ($request->user()->fresh()->hasVerifiedEmail() ? 'VERIFIED' : 'NOT VERIFIED'));

        event(new Verified($request->user()));

        return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
    }
}
