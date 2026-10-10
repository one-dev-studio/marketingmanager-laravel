<?php

namespace App\Providers;

use App\Models\ContentApproval;
use App\Models\ProjectTemplate;
use App\Models\ScheduledPost;
use App\Models\TaskTemplate;
use App\Models\User;
use App\Policies\AdminUserPolicy;
use App\Policies\ContentPolicy;
use App\Policies\ProjectTemplatePolicy;
use App\Policies\ReviewPolicy;
use App\Policies\TaskTemplatePolicy;
use App\Repositories\Contracts\CampaignRepositoryInterface;
use App\Repositories\Eloquent\CampaignRepository;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        ContentApproval::class => ReviewPolicy::class,
        TaskTemplate::class => TaskTemplatePolicy::class,
        ProjectTemplate::class => ProjectTemplatePolicy::class,
        User::class => AdminUserPolicy::class,
        ScheduledPost::class => ContentPolicy::class,
    ];

    public function register(): void
    {
        $this->app->bind(CampaignRepositoryInterface::class, CampaignRepository::class);
    }

    public function boot(): void
    {
        $appUrl = config('app.url');
        if (is_string($appUrl) && $appUrl !== '') {
            URL::forceRootUrl($appUrl);
        }

        Blade::anonymousComponentPath(resource_path('views/partials/layout'), 'partials.layout');

        // Register policy mappings
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }
}
