<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Validation\ValidationException;

class TwoFactorChallengeController extends Controller
{
    public function create()
    {
        return Inertia::render('Auth/TwoFactorChallenge');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        $user = $request->user();
        $google2fa = new Google2FA();
        
        if ($user->google2fa_secret && $google2fa->verifyKey($user->google2fa_secret, $request->code)) {
            $request->session()->put('auth.2fa_verified', true);
            return redirect()->intended(route('dashboard'));
        }

        throw ValidationException::withMessages([
            'code' => __('The provided two factor authentication code was invalid.'),
        ]);
    }
}
