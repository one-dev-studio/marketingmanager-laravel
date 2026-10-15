<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Contracts\User as SocialiteUser;

class GoogleAuthService
{
    /**
     * Find or create a local user from a Google Socialite user payload.
     *
     * @throws ValidationException
     */
    public function resolveUserFromGoogle(SocialiteUser $googleUser): User
    {
        if (! $this->isGoogleEmailVerified($googleUser)) {
            throw ValidationException::withMessages([
                'email' => __('Google did not verify this email address. Sign in with email and password or use a verified Google account.'),
            ]);
        }

        $googleId = (string) $googleUser->getId();
        $email = strtolower(trim((string) $googleUser->getEmail()));

        if ($googleId === '' || $email === '') {
            throw ValidationException::withMessages([
                'email' => __('Unable to sign in with Google. Missing account details from Google.'),
            ]);
        }

        $avatarUrl = $this->resolveAvatarUrl($googleUser);

        return DB::transaction(function () use ($googleId, $email, $avatarUrl, $googleUser): User {
            $userByGoogleId = User::query()->where('google_id', $googleId)->first();

            if ($userByGoogleId !== null) {
                if (strtolower($userByGoogleId->email) !== $email) {
                    throw ValidationException::withMessages([
                        'email' => __('This Google account cannot be linked because the email does not match your existing account.'),
                    ]);
                }

                return $this->syncGoogleProfile($userByGoogleId, $googleId, $avatarUrl);
            }

            $userByEmail = User::query()->where('email', $email)->first();

            if ($userByEmail !== null) {
                if ($userByEmail->google_id !== null && $userByEmail->google_id !== $googleId) {
                    throw ValidationException::withMessages([
                        'email' => __('This email is already linked to a different Google account.'),
                    ]);
                }

                return $this->syncGoogleProfile($userByEmail, $googleId, $avatarUrl);
            }

            return User::query()->create([
                'name' => $this->resolveDisplayName($googleUser, $email),
                'email' => $email,
                'password' => Hash::make(Str::password(32)),
                'google_id' => $googleId,
                'avatar' => $avatarUrl,
                'user_type' => 'customer',
                'email_verified_at' => now(),
            ]);
        });
    }

    private function syncGoogleProfile(User $user, string $googleId, ?string $avatarUrl): User
    {
        $updates = [];

        if ($user->google_id === null) {
            $updates['google_id'] = $googleId;
        }

        if ($avatarUrl !== null && ($user->avatar === null || $user->avatar === '')) {
            $updates['avatar'] = $avatarUrl;
        }

        if ($user->email_verified_at === null) {
            $updates['email_verified_at'] = now();
        }

        if ($updates !== []) {
            $user->update($updates);
        }

        return $user->fresh() ?? $user;
    }

    private function isGoogleEmailVerified(SocialiteUser $googleUser): bool
    {
        $raw = $googleUser->getRaw();

        if (! is_array($raw)) {
            return false;
        }

        $verified = Arr::get($raw, 'email_verified', Arr::get($raw, 'verified_email', false));

        return filter_var($verified, FILTER_VALIDATE_BOOLEAN);
    }

    private function resolveAvatarUrl(SocialiteUser $googleUser): ?string
    {
        $avatar = $googleUser->getAvatar();

        if (! is_string($avatar) || $avatar === '') {
            return null;
        }

        if (! filter_var($avatar, FILTER_VALIDATE_URL)) {
            return null;
        }

        return $avatar;
    }

    private function resolveDisplayName(SocialiteUser $googleUser, string $email): string
    {
        $name = trim((string) $googleUser->getName());

        if ($name !== '') {
            return $name;
        }

        return Str::before($email, '@');
    }
}
