<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles {
        hasRole as spatieHasRole;
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'timezone',
        'locale',
        'country_code',
        'status',
        'user_type',
        'email_verified_at',
        'two_factor_enabled',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_enabled' => 'boolean',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'user_roles')
            ->withPivot('role_id')
            ->withTimestamps();
    }

    public function primaryOrganization()
    {
        return $this->organizations()->first();
    }

    public function agencies()
    {
        return $this->belongsToMany(Agency::class, 'agency_team_members')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function primaryAgency()
    {
        return $this->agencies()->first();
    }

    /**
     * Get campaigns created by this user
     */
    public function createdCampaigns(): HasMany
    {
        return $this->hasMany(Campaign::class, 'created_by');
    }

    /**
     * Get scheduled posts created by this user
     */
    public function scheduledPosts(): HasMany
    {
        return $this->hasMany(ScheduledPost::class, 'created_by');
    }

    /**
     * Get tasks assigned to this user
     */
    public function assignedTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'assignee_id');
    }

    /**
     * Get tasks created by this user
     */
    public function createdTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'created_by');
    }

    /**
     * Get projects created by this user
     */
    public function createdProjects(): HasMany
    {
        return $this->hasMany(Project::class, 'created_by');
    }

    /**
     * Get projects this user is a member of
     */
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_members')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Get AI generations created by this user
     */
    public function aiGenerations(): HasMany
    {
        return $this->hasMany(AiGeneration::class);
    }

    /**
     * Get activity logs for this user
     */
    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function isAdmin(): bool
    {
        return $this->user_type === 'admin';
    }

    public function isAgency(): bool
    {
        return $this->user_type === 'agency';
    }

    public function isCustomer(): bool
    {
        return $this->user_type === 'customer';
    }

    public function hasAccessToOrganization(int $organizationId): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        return $this->organizations()->where('organizations.id', $organizationId)->exists();
    }

    /**
     * Spatie's hasRole($roles, $guard) treats the second argument as a guard name.
     * This app stores tenant roles on user_roles / agency_team_members, so when an
     * Organization or Agency is passed, check that pivot instead.
     */
    public function hasRole($roles, $guard = null): bool
    {
        if ($guard instanceof Organization || $guard instanceof Agency) {
            return $this->hasTenantRole($roles, $guard);
        }

        if (is_array($roles) || $roles instanceof \Illuminate\Support\Enumerable) {
            foreach ($roles as $role) {
                if ($this->hasRole($role, $guard)) {
                    return true;
                }
            }

            return false;
        }

        if (! is_string($roles) && ! is_int($roles) && ! $roles instanceof \Spatie\Permission\Contracts\Role) {
            return false;
        }

        return $this->spatieHasRole($roles, is_string($guard) ? $guard : null);
    }

    public function hasTenantRole(string|array $roles, Organization|Agency $tenant): bool
    {
        $names = $this->normalizeRoleNames($roles);

        if ($tenant instanceof Organization) {
            $pivotRole = $this->organizations()
                ->where('organizations.id', $tenant->id)
                ->first()
                ?->pivot
                ?->role_id;

            if (! $pivotRole) {
                return false;
            }

            $roleName = Role::find($pivotRole)?->name;

            return $roleName !== null && in_array($roleName, $names, true);
        }

        $pivotRole = $this->agencies()
            ->where('agencies.id', $tenant->id)
            ->first()
            ?->pivot
            ?->role;

        return $pivotRole !== null && in_array($pivotRole, $names, true);
    }

    private function normalizeRoleNames(string|array $roles): array
    {
        $names = [];
        foreach ((array) $roles as $role) {
            $names[] = $role;
            $names[] = str_replace('-', '_', $role);
            $names[] = str_replace('_', '-', $role);
        }

        return array_values(array_unique($names));
    }

    public function isAgencyMember(int $agencyId): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        return $this->agencies()->where('agencies.id', $agencyId)->exists();
    }

    /**
     * Get the user's preferred locale or fallback to default
     */
    public function getPreferredLocale(): string
    {
        return $this->locale ?? config('localization.default_locale', 'en');
    }

    /**
     * Set the user's locale preference
     */
    public function setPreferredLocale(string $locale): void
    {
        $this->update(['locale' => $locale]);
    }

    /**
     * Check if user has a custom locale set
     */
    public function hasCustomLocale(): bool
    {
        return !empty($this->locale);
    }

    /**
     * Get user sessions relationship
     */
    public function sessions(): HasMany
    {
        return $this->hasMany(UserSession::class);
    }

    /**
     * Check if two-factor authentication is enabled
     */
    public function hasTwoFactorEnabled(): bool
    {
        return $this->two_factor_enabled && $this->two_factor_confirmed_at !== null;
    }

    /**
     * Send the email verification notification
     */
    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new \App\Notifications\VerifyEmail);
    }
}

