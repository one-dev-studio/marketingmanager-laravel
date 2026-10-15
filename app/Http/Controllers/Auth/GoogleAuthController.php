<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\GoogleAuthService;
use App\Services\Auth\PostAuthenticationRedirect;
use App\Services\Auth\SessionService;
use App\Support\GoogleAuthConfig;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse as SymfonyRedirectResponse;

class GoogleAuthController extends Controller
{
    public function __construct(
        private GoogleAuthService $googleAuthService,
        private SessionService $sessionService,
        private PostAuthenticationRedirect $postAuthenticationRedirect,
    ) {}

    /**
     * Redirect the guest to Google's OAuth consent screen.
     */
    public function redirect(Request $request): SymfonyRedirectResponse|RedirectResponse
    {
        if (! GoogleAuthConfig::isEnabled()) {
            abort(404);
        }

        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle the Google OAuth callback and sign the user in.
     */
    public function callback(Request $request): RedirectResponse
    {
        if (! GoogleAuthConfig::isEnabled()) {
            abort(404);
        }

        try {
            $googleUser = Socialite::driver('google')->user();
            $user = $this->googleAuthService->resolveUserFromGoogle($googleUser);
        } catch (ValidationException $exception) {
            return redirect()
                ->route('login')
                ->withErrors($exception->errors());
        } catch (\Throwable $exception) {
            Log::warning('Google OAuth callback failed', [
                'message' => $exception->getMessage(),
            ]);

            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => __('Unable to sign in with Google. Please try again or use email and password.'),
                ]);
        }

        if ($user->hasTwoFactorEnabled() && ! $request->session()->has('two_factor_verified')) {
            $request->session()->put('login.id', $user->id);
            Auth::logout();

            return redirect()->route('two-factor.challenge');
        }

        Auth::login($user, remember: true);
        $request->session()->regenerate();
        $this->sessionService->createOrUpdateSession($user, $request);

        $redirect = $this->postAuthenticationRedirect->redirectFor($user);

        if ($request->session()->has('url.intended')) {
            $intended = $request->session()->pull('url.intended');

            if ($this->isSafeInternalRedirect($intended)) {
                return redirect()->to($intended);
            }
        }

        return $redirect;
    }

    private function isSafeInternalRedirect(mixed $url): bool
    {
        if (! is_string($url) || $url === '') {
            return false;
        }

        if (! str_starts_with($url, '/') || str_starts_with($url, '//')) {
            return false;
        }

        return ! preg_match('#^/\s*https?://#i', $url);
    }
}
