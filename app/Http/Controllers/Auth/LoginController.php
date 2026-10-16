<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\PostAuthenticationRedirect;
use App\Services\Auth\SessionService;
use App\Support\GoogleAuthConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class LoginController extends Controller
{
    public function __construct(
        private SessionService $sessionService,
        private PostAuthenticationRedirect $postAuthenticationRedirect,
    ) {}

    public function showLoginForm(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => true,
            'googleAuthEnabled' => GoogleAuthConfig::isEnabled(),
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $user = Auth::user();

            if ($user->hasTwoFactorEnabled() && ! $request->session()->has('two_factor_verified')) {
                $request->session()->put('login.id', $user->id);
                Auth::logout();

                return redirect()->route('two-factor.challenge');
            }

            $request->session()->regenerate();
            $this->sessionService->createOrUpdateSession($user, $request);

            return $this->authenticated($request, $user);
        }

        throw ValidationException::withMessages([
            'email' => __('The provided credentials do not match our records.'),
        ]);
    }

    protected function authenticated(Request $request, $user)
    {
        return $this->postAuthenticationRedirect->redirectFor($user);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        $sessionId = $request->session()->getId();

        if ($user && $sessionId) {
            $this->sessionService->revokeSession($user, $sessionId);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
