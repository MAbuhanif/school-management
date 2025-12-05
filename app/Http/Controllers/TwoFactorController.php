<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorController extends Controller
{
    public function enable(Request $request)
    {
        $google2fa = new Google2FA();
        $secret = $google2fa->generateSecretKey();
        
        $user = $request->user();
        
        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );

        return Inertia::render('Profile/TwoFactor', [
            'secret' => $secret,
            'qrCodeUrl' => $qrCodeUrl,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'secret' => 'required|string',
            'code' => 'required|string',
        ]);

        $google2fa = new Google2FA();
        $valid = $google2fa->verifyKey($validated['secret'], $validated['code']);

        if (!$valid) {
            return back()->withErrors(['code' => 'Invalid authentication code.']);
        }

        $request->user()->forceFill([
            'google2fa_secret' => $validated['secret'],
        ])->save();

        return redirect()->route('profile.edit')->with('success', 'Two-Factor Authentication enabled.');
    }

    public function disable(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password',
        ]);

        $request->user()->forceFill([
            'google2fa_secret' => null,
        ])->save();

        return back()->with('success', 'Two-Factor Authentication disabled.');
    }
}
