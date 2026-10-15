<?php

namespace App\Providers;

use App\Models\ContentApproval;
use App\Models\PaymentWebhookSetting;
use App\Models\ProjectTemplate;
use App\Models\ScheduledPost;
use App\Models\TaskTemplate;
use App\Models\User;
use App\Policies\AdminPaymentWebhookSettingsPolicy;
use App\Policies\AdminUserPolicy;
use App\Policies\ContentPolicy;
use App\Policies\ProjectTemplatePolicy;
use App\Policies\ReviewPolicy;
use App\Policies\TaskTemplatePolicy;
use App\Repositories\Contracts\CampaignRepositoryInterface;
use App\Repositories\Eloquent\CampaignRepository;
use App\Services\Broadcasting\BroadcastingConfiguration;
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
        PaymentWebhookSetting::class => AdminPaymentWebhookSettingsPolicy::class,
        ScheduledPost::class => ContentPolicy::class,
    ];

    public function register(): void
    {
        $this->app->singleton(BroadcastingConfiguration::class);

        $this->app->bind(CampaignRepositoryInterface::class, CampaignRepository::class);
    }

    public function boot(): void
    {
        // Keep generated URLs aligned with the public APP_URL on deployed environments.
        // In local dev, forcing APP_URL (often http://localhost) breaks Vite assets and CSP when
        // the app is opened via 127.0.0.1 or a non-default port.
        $appUrl = config('app.url');
        if (
            is_string($appUrl)
            && $appUrl !== ''
            && app()->environment('production', 'staging')
        ) {
            URL::forceRootUrl($appUrl);
        }

        // Register policy mappings
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }
}
