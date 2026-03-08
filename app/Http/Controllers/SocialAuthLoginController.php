<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthLoginController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(): RedirectResponse
    {
        $user = Socialite::driver('google')->user();
        $existingUser = User::withTrashed()->where('email', $user->getEmail())->first();

        if ($existingUser?->trashed()) {
            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'This account has been deactivated. Please contact an administrator.',
                ])
                ->withInput([
                    'email' => $user->getEmail(),
                ]);
        }

        if ($existingUser?->is_locked) {
            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Your account is locked. Please contact an administrator.',
                ])
                ->withInput([
                    'email' => $user->getEmail(),
                ]);
        }

        if ($existingUser) {
            Auth::login($existingUser);
        } else {
            $newUser = User::create([
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'email_verified_at' => now(), // Mark email as verified only for social logins
                'is_locked' => false,
            ]);

            Auth::login($newUser);
        }

        return redirect()->route('dashboard');
    }
}
