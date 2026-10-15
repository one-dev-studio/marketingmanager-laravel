<?php

use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\CostingController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\PaymentWebhookSettingsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Agency\CalendarController;
use App\Http\Controllers\Agency\ClientController;
use App\Http\Controllers\AI\AiContentController;
use App\Http\Controllers\AI\AiImageController;
use App\Http\Controllers\AI\AiUsageController;
use App\Http\Controllers\AI\LabelInspirationController;
use App\Http\Controllers\AI\ProductCatalogController;
use App\Http\Controllers\AI\SeoController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\Auth\ConfirmPasswordController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\Billing\PaymentWebhookController;
use App\Http\Controllers\Brand\BrandAssetController;
use App\Http\Controllers\Brand\BrandController;
use App\Http\Controllers\Brand\BrandNameGeneratorController;
use App\Http\Controllers\Campaign\CampaignController;
use App\Http\Controllers\Campaign\CampaignTemplateController;
use App\Http\Controllers\Campaign\CompetitionController;
use App\Http\Controllers\Campaign\ContentManagementController;
use App\Http\Controllers\Campaign\ContestController;
use App\Http\Controllers\Campaign\PaidCampaignController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\Chat\ChatReactionController;
use App\Http\Controllers\Chatbot\ChatbotController;
use App\Http\Controllers\ClientPortal\PortalController;
use App\Http\Controllers\ClientPortal\WorkspaceController;
use App\Http\Controllers\CollaborationController;
use App\Http\Controllers\Competitor\CompetitorController;
use App\Http\Controllers\ContentApprovalController;
use App\Http\Controllers\ContentCalendarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardWidgetController;
use App\Http\Controllers\EmailMarketing\ContactController;
use App\Http\Controllers\EmailMarketing\ContactListController;
use App\Http\Controllers\EmailMarketing\EmailCampaignController;
use App\Http\Controllers\EmailMarketing\EmailTemplateController;
use App\Http\Controllers\EmailMarketing\EmailTrackingController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\Inertia\DemoController;
use App\Http\Controllers\Intelligence\IntelligenceController;
use App\Http\Controllers\LandingPage\LandingPageController;
use App\Http\Controllers\LandingPage\PublicLandingPageController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\Notification\NotificationController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\Organization\AutomationController;
use App\Http\Controllers\Organization\BillingController;
use App\Http\Controllers\Organization\SettingsController;
use App\Http\Controllers\Organization\StorageSourceController;
use App\Http\Controllers\Organization\TeamController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\PressRelease\PressContactController;
use App\Http\Controllers\PressRelease\PressReleaseController;
use App\Http\Controllers\Product\ProductCategoryController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Product\ProductImageController;
use App\Http\Controllers\Product\ProductVariantController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Project\ProjectController;
use App\Http\Controllers\Project\ProjectTemplateController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Review\ReviewController;
use App\Http\Controllers\SocialMedia\PublishingController;
use App\Http\Controllers\SocialMedia\SocialAuthController;
use App\Http\Controllers\SocialMedia\SocialConnectionController;
use App\Http\Controllers\SocialMedia\WebhookController;
use App\Http\Controllers\Survey\PublicSurveyController;
use App\Http\Controllers\Survey\SurveyController;
use App\Http\Controllers\Task\TaskController;
use App\Http\Controllers\Task\TaskTemplateController;
use App\Http\Controllers\Tools\ToolsHubController;
use App\Http\Controllers\Workflow\WorkflowController;
use Illuminate\Support\Facades\Route;

// Locale switching routes
Route::get('/locale/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');
Route::get('/locale/regional/{regionalLocale}', [LocaleController::class, 'switchRegional'])->name('locale.switch.regional');

// Public Marketing Pages
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/features', [PublicController::class, 'features'])->name('features');
Route::get('/pricing', [PublicController::class, 'pricing'])->name('pricing');
Route::get('/about', [PublicController::class, 'about'])->name('about');
Route::get('/contact', [PublicController::class, 'contact'])->name('contact');
Route::post('/contact', [PublicController::class, 'submitContact'])->name('contact.submit');
Route::get('/privacy', [PublicController::class, 'legal'])->defaults('page', 'privacy')->name('privacy');
Route::get('/terms', [PublicController::class, 'legal'])->defaults('page', 'terms')->name('terms');
Route::get('/cookies', [PublicController::class, 'legal'])->defaults('page', 'cookies')->name('cookies');
Route::get('/gdpr', [PublicController::class, 'legal'])->defaults('page', 'gdpr')->name('gdpr');
Route::get('/help', [PublicController::class, 'legal'])->defaults('page', 'help')->name('help');
Route::get('/blog', [PublicController::class, 'legal'])->defaults('page', 'blog')->name('blog');
Route::get('/careers', [PublicController::class, 'legal'])->defaults('page', 'careers')->name('careers');
Route::get('/integrations', [PublicController::class, 'legal'])->defaults('page', 'integrations')->name('integrations');
Route::get('/developer', [PublicController::class, 'legal'])->defaults('page', 'api')->name('developer');
Route::get('/sitemap.xml', [PublicController::class, 'sitemap'])->name('sitemap');

// Phase 0: Inertia + React scaffold proof (unlisted; does not replace existing flows)
Route::get('/inertia/demo', [DemoController::class, 'show'])
    ->name('inertia.demo');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/signup', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/signup', [RegisterController::class, 'register']);

    Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('auth.google.redirect');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');

    // Password reset routes
    Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::prefix('admin')->middleware('guest:admin')->group(function () {
    Route::get('/login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// Email verification routes
Route::middleware(['auth'])->group(function () {
    Route::get('/email/verify', [EmailVerificationController::class, 'showNotice'])
        ->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('/user/confirm-password', [ConfirmPasswordController::class, 'show'])
        ->name('password.confirm');
    Route::post('/user/confirm-password', [ConfirmPasswordController::class, 'store'])
        ->name('password.confirm.store');
});

// Two-factor authentication routes
Route::middleware(['auth'])->prefix('two-factor')->name('two-factor.')->group(function () {
    Route::get('/setup', [TwoFactorController::class, 'showSetupForm'])->name('setup');
    Route::post('/enable', [TwoFactorController::class, 'enable'])->name('enable');
    Route::post('/disable', [TwoFactorController::class, 'disable'])->name('disable');
});

Route::middleware(['guest'])->prefix('two-factor')->name('two-factor.')->group(function () {
    Route::get('/challenge', [TwoFactorController::class, 'showChallengeForm'])->name('challenge');
    Route::post('/verify', [TwoFactorController::class, 'verifyChallenge'])->name('verify');
});

// Profile routes
Route::middleware(['auth', 'verified'])->prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'show'])->name('show');
    Route::put('/update', [ProfileController::class, 'updateProfile'])->name('update');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::put('/avatar', [ProfileController::class, 'updateAvatar'])->name('avatar.update');
    Route::get('/security', [ProfileController::class, 'showSecurity'])->name('security');
    Route::delete('/sessions/{sessionId}', [ProfileController::class, 'revokeSession'])->name('sessions.revoke');
    Route::post('/sessions/revoke-other', [ProfileController::class, 'revokeOtherSessions'])->name('sessions.revoke-other');
});

Route::middleware(['auth', 'verified', 'client.portal'])
    ->prefix('client-portal/{agency}')
    ->name('client-portal.')
    ->group(function () {
        Route::get('/', [PortalController::class, 'home'])->name('home');
        Route::get('/workspaces/{organization}', [WorkspaceController::class, 'show'])
            ->name('workspace');
    });

Route::middleware(['auth', 'verified'])->prefix('main')->name('main.')->group(function () {
    Route::get('/organizations', [OrganizationController::class, 'index'])->name('organizations');
    Route::get('/onboarding', [OnboardingController::class, 'index'])->name('onboarding');

    Route::middleware(['tenant', 'organization'])->prefix('{organizationId}')->group(function () {
        Route::get('/inertia/shell', [DemoController::class, 'shell'])->name('inertia.shell');

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::prefix('collaboration')->name('collaboration.')->group(function () {
            Route::get('/', [CollaborationController::class, 'index'])->name('index');
            Route::post('/topics', [CollaborationController::class, 'createTopic'])->name('topics.create');
            Route::get('/topics/{topic}', [CollaborationController::class, 'showTopic'])->name('topics.show');
            Route::post('/topics/{topic}/messages', [CollaborationController::class, 'storeMessage'])->name('messages.store');

            // Chat Reactions
            Route::prefix('messages/{chatMessage}')->name('messages.')->group(function () {
                Route::post('/reactions', [ChatReactionController::class, 'addReaction'])->name('reactions.add');
                Route::delete('/reactions/{reaction}', [ChatReactionController::class, 'removeReaction'])->name('reactions.remove');
                Route::get('/reactions', [ChatReactionController::class, 'getReactions'])->name('reactions.index');
            });
        });

        // Review (Content Approval)
        Route::middleware('brand.context')->prefix('review')->name('review.')->group(function () {
            Route::get('/', [ContentApprovalController::class, 'index'])->name('index');
            Route::get('/{approval}', [ContentApprovalController::class, 'show'])->name('show');
            Route::post('/{approval}/approve', [ContentApprovalController::class, 'approve'])->name('approve');
            Route::post('/{approval}/reject', [ContentApprovalController::class, 'reject'])->name('reject');
            Route::get('/scheduled-posts/{scheduledPost}/history', [ContentApprovalController::class, 'getApprovalHistory'])->name('history');
        });

        // Tasks
        Route::middleware('not.client')->prefix('tasks')->name('tasks.')->group(function () {
            Route::get('/', [TaskController::class, 'index'])->name('index');
            Route::post('/', [TaskController::class, 'store'])->name('store');
            Route::get('/{task}', [TaskController::class, 'show'])->name('show');
            Route::put('/{task}', [TaskController::class, 'update'])->name('update');
            Route::delete('/{task}', [TaskController::class, 'destroy'])->name('destroy');
            Route::post('/{task}/assign', [TaskController::class, 'assign'])->name('assign');
            Route::post('/{task}/status', [TaskController::class, 'updateStatus'])->name('status');
            Route::post('/{task}/comments', [TaskController::class, 'addComment'])->name('comments.store');
            Route::post('/{task}/attachments', [TaskController::class, 'addAttachment'])->name('attachments.store');
            Route::delete('/attachments/{taskAttachment}', [TaskController::class, 'deleteAttachment'])->name('attachments.destroy');

            // Task Templates
            Route::prefix('templates')->name('templates.')->group(function () {
                Route::get('/', [TaskTemplateController::class, 'index'])->name('index');
                Route::post('/', [TaskTemplateController::class, 'store'])->name('store');
                Route::get('/{taskTemplate}', [TaskTemplateController::class, 'show'])->name('show');
                Route::put('/{taskTemplate}', [TaskTemplateController::class, 'update'])->name('update');
                Route::delete('/{taskTemplate}', [TaskTemplateController::class, 'destroy'])->name('destroy');
                Route::post('/{taskTemplate}/create-task', [TaskTemplateController::class, 'createTask'])->name('create-task');
            });
        });

        // Projects
        Route::middleware('not.client')->prefix('projects')->name('projects.')->group(function () {
            Route::get('/', [ProjectController::class, 'index'])->name('index');
            Route::post('/', [ProjectController::class, 'store'])->name('store');
            Route::get('/{project}', [ProjectController::class, 'show'])->name('show');
            Route::put('/{project}', [ProjectController::class, 'update'])->name('update');
            Route::delete('/{project}', [ProjectController::class, 'destroy'])->name('destroy');
            Route::post('/{project}/members', [ProjectController::class, 'addMember'])->name('members.store');
            Route::delete('/{project}/members/{userId}', [ProjectController::class, 'removeMember'])->name('members.destroy');
            Route::put('/{project}/members/{userId}/role', [ProjectController::class, 'updateMemberRole'])->name('members.role');
            Route::post('/{project}/status', [ProjectController::class, 'updateStatus'])->name('status');

            // Project Templates
            Route::prefix('templates')->name('templates.')->group(function () {
                Route::get('/', [ProjectTemplateController::class, 'index'])->name('index');
                Route::post('/', [ProjectTemplateController::class, 'store'])->name('store');
                Route::get('/{projectTemplate}', [ProjectTemplateController::class, 'show'])->name('show');
                Route::put('/{projectTemplate}', [ProjectTemplateController::class, 'update'])->name('update');
                Route::delete('/{projectTemplate}', [ProjectTemplateController::class, 'destroy'])->name('destroy');
                Route::post('/{projectTemplate}/create-project', [ProjectTemplateController::class, 'createProject'])->name('create-project');
            });
        });

        // Notifications
        Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/', [NotificationController::class, 'index'])->name('index');
            Route::get('/unread-count', [NotificationController::class, 'unreadCount'])->name('unread-count');
            Route::post('/{notification}/read', [NotificationController::class, 'markAsRead'])->name('read');
            Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('read-all');
            Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('destroy');
            Route::get('/preferences', [NotificationController::class, 'preferences'])->name('preferences');
            Route::put('/preferences', [NotificationController::class, 'updatePreference'])->name('preferences.update');
        });

        Route::middleware('not.client')->prefix('campaigns')->name('campaigns.')->group(function () {
            Route::get('/', [CampaignController::class, 'index'])->name('index');
            Route::get('/create', [CampaignController::class, 'create'])->name('create');
            Route::post('/', [CampaignController::class, 'store'])->name('store');
            Route::post('/generate-plan', [CampaignController::class, 'generatePlan'])->name('generate-plan');
            Route::post('/ai/suggestions', [CampaignController::class, 'getAISuggestions'])->name('ai.suggestions');
            Route::get('/{campaign}', [CampaignController::class, 'show'])->name('show');
            Route::put('/{campaign}', [CampaignController::class, 'update'])->name('update');
            Route::delete('/{campaign}', [CampaignController::class, 'destroy'])->name('destroy');
            Route::post('/{campaign}/submit-for-review', [CampaignController::class, 'submitForReview'])->name('submit-for-review');
            Route::post('/{campaign}/generate-content', [CampaignController::class, 'generateContent'])->name('generate-content');
            Route::post('/{campaign}/publish', [CampaignController::class, 'publish'])->name('publish');
            Route::post('/{campaign}/pause', [CampaignController::class, 'pause'])->name('pause');
            Route::post('/{campaign}/resume', [CampaignController::class, 'resume'])->name('resume');
            Route::post('/{campaign}/complete', [CampaignController::class, 'complete'])->name('complete');
            Route::post('/{campaign}/deactivate', [CampaignController::class, 'deactivate'])->name('deactivate');
            Route::post('/{campaign}/reactivate', [CampaignController::class, 'reactivate'])->name('reactivate');
            Route::post('/{campaign}/clone', [CampaignController::class, 'clone'])->name('clone');
            Route::post('/{campaign}/products', [CampaignController::class, 'attachProducts'])->name('attach-products');
            Route::delete('/{campaign}/products', [CampaignController::class, 'detachProducts'])->name('detach-products');

            // Content Management
            Route::prefix('{campaign}/content')->name('content.')->group(function () {
                Route::get('/', [ContentManagementController::class, 'index'])->name('index');
                Route::post('/', [ContentManagementController::class, 'store'])->name('store');
                Route::put('/{scheduledPost}', [ContentManagementController::class, 'update'])->name('update');
                Route::delete('/{scheduledPost}', [ContentManagementController::class, 'destroy'])->name('destroy');
            });

            // Competition
            Route::prefix('{campaign}/competitions')->name('competitions.')->group(function () {
                Route::get('/', [CompetitionController::class, 'index'])->name('index');
                Route::post('/attach', [CompetitionController::class, 'attachCompetitor'])->name('attach');
                Route::delete('/{competitor}', [CompetitionController::class, 'detachCompetitor'])->name('detach');
            });
        });

        // Campaign Templates
        Route::prefix('campaign-templates')->name('campaign-templates.')->group(function () {
            Route::get('/', [CampaignTemplateController::class, 'index'])->name('index');
            Route::post('/', [CampaignTemplateController::class, 'store'])->name('store');
            Route::get('/{template}', [CampaignTemplateController::class, 'show'])->name('show');
            Route::put('/{template}', [CampaignTemplateController::class, 'update'])->name('update');
            Route::delete('/{template}', [CampaignTemplateController::class, 'destroy'])->name('destroy');
            Route::post('/{template}/create-campaign', [CampaignTemplateController::class, 'createCampaignFromTemplate'])->name('create-campaign');
        });

        // Paid Campaigns
        Route::middleware('not.client')->prefix('paid-campaigns')->name('paid-campaigns.')->group(function () {
            Route::get('/', [PaidCampaignController::class, 'index'])->name('index');
            Route::post('/', [PaidCampaignController::class, 'store'])->name('store');
            Route::get('/{paidCampaign}', [PaidCampaignController::class, 'show'])->name('show');
            Route::put('/{paidCampaign}', [PaidCampaignController::class, 'update'])->name('update');
            Route::delete('/{paidCampaign}', [PaidCampaignController::class, 'destroy'])->name('destroy');
            Route::post('/{paidCampaign}/metrics', [PaidCampaignController::class, 'updateMetrics'])->name('metrics');
        });

        // Content Calendar
        Route::prefix('content-calendar')->name('content-calendar.')->group(function () {
            Route::get('/', [ContentCalendarController::class, 'index'])->name('index');
            Route::post('/', [ContentCalendarController::class, 'store'])->name('store');
            Route::put('/{event}', [ContentCalendarController::class, 'update'])->name('update');
            Route::delete('/{event}', [ContentCalendarController::class, 'destroy'])->name('destroy');
            Route::post('/bulk-schedule', [ContentCalendarController::class, 'bulkSchedule'])->name('bulk-schedule');
            Route::post('/recurring', [ContentCalendarController::class, 'createRecurringSchedule'])->name('recurring');
            Route::delete('/recurring/{scheduledPost}', [ContentCalendarController::class, 'cancelRecurringSchedule'])->name('cancel-recurring');
        });

        // Content Approval
        Route::prefix('content-approvals')->name('content-approvals.')->group(function () {
            Route::get('/', [ContentApprovalController::class, 'index'])->name('index');
            Route::get('/queue', [ContentApprovalController::class, 'queue'])->name('queue');
            Route::post('/bulk', [ContentApprovalController::class, 'bulk'])->name('bulk');
            Route::get('/{approval}', [ContentApprovalController::class, 'show'])->name('show');
            Route::post('/{scheduledPost}/request', [ContentApprovalController::class, 'requestApproval'])->name('request');
            Route::post('/{approval}/approve', [ContentApprovalController::class, 'approve'])->name('approve');
            Route::post('/{approval}/reject', [ContentApprovalController::class, 'reject'])->name('reject');
            Route::get('/{scheduledPost}/history', [ContentApprovalController::class, 'getApprovalHistory'])->name('history');
            Route::get('/{approval}/annotations', [ContentApprovalController::class, 'annotations'])->name('annotations');
        });

        // Dashboard API endpoints
        Route::prefix('dashboard')->name('dashboard.')->group(function () {
            Route::get('/kpis', [DashboardController::class, 'getKPIs'])->name('kpis');
            Route::get('/activity-feed', [DashboardController::class, 'getActivityFeed'])->name('activity-feed');
            Route::get('/pending-tasks', [DashboardController::class, 'getPendingTasks'])->name('pending-tasks');
            Route::get('/content-calendar', [DashboardController::class, 'getContentCalendar'])->name('content-calendar');

            // Dashboard Widgets
            Route::prefix('widgets')->name('widgets.')->group(function () {
                Route::get('/', [DashboardWidgetController::class, 'index'])->name('index');
                Route::post('/', [DashboardWidgetController::class, 'store'])->name('store');
                Route::put('/positions', [DashboardWidgetController::class, 'updatePositions'])->name('update-positions');
                Route::put('/{widget}', [DashboardWidgetController::class, 'update'])->name('update');
                Route::delete('/{widget}', [DashboardWidgetController::class, 'destroy'])->name('destroy');
            });
        });

        // Tools Pages (Content Ideation)
        Route::middleware('not.client')->prefix('tools')->name('tools.')->group(function () {
            Route::get('/', [ToolsHubController::class, 'index'])->name('index');
            Route::get('/seo-analysis', [ToolsHubController::class, 'seoAnalysis'])->name('seo-analysis');
            Route::get('/email-template', [ToolsHubController::class, 'emailTemplate'])->name('email-template');
            Route::get('/image-generator', [ToolsHubController::class, 'imageGenerator'])->name('image-generator');
            Route::get('/blog', [ToolsHubController::class, 'blog'])->name('blog');
            Route::get('/press-release', [ToolsHubController::class, 'pressRelease'])->name('press-release');
            Route::get('/label-inspiration', [LabelInspirationController::class, 'index'])->name('label-inspiration');
            Route::get('/product-catalog', [ProductCatalogController::class, 'index'])->name('product-catalog');
        });

        Route::middleware('not.client')->prefix('intelligence')->name('intelligence.')->group(function () {
            Route::get('/sentiment', [IntelligenceController::class, 'sentiment'])->name('sentiment');
            Route::get('/predictive', [IntelligenceController::class, 'predictive'])->name('predictive');
        });

        Route::middleware('not.client')->prefix('paid-ads')->name('paid-ads.')->group(function () {
            Route::get('/campaigns', [PaidCampaignController::class, 'index'])->name('campaigns');
            Route::get('/ad-copy', [ToolsHubController::class, 'adCopy'])->name('ad-copy');
            Route::get('/keyword-research', [ToolsHubController::class, 'keywordResearch'])->name('keyword-research');
        });

        Route::middleware('not.client')->prefix('competitions')->name('competitions.')->group(function () {
            Route::get('/', [ContestController::class, 'index'])->name('index');
            Route::get('/create', [ContestController::class, 'create'])->name('create');
            Route::post('/', [ContestController::class, 'store'])->name('store');
            Route::get('/{contest}', [ContestController::class, 'show'])->name('show');
            Route::post('/{contest}/entries', [ContestController::class, 'storeEntry'])->name('entries.store');
            Route::post('/{contest}/close', [ContestController::class, 'close'])->name('close');
        });

        Route::middleware('not.client')->prefix('reputation')->name('reviews.')->group(function () {
            Route::get('/', [ReviewController::class, 'index'])->name('index');
            Route::get('/{review}', [ReviewController::class, 'show'])->name('show');
            Route::post('/{review}/responses', [ReviewController::class, 'createResponse'])->name('responses.store');
            Route::post('/import', [ReviewController::class, 'importReviews'])->name('import');
            Route::get('/sources/list', [ReviewController::class, 'sources'])->name('sources');
        });

        // AI Content Generation
        Route::prefix('ai')->name('ai.')->group(function () {
            Route::prefix('content')->name('content.')->group(function () {
                Route::post('/social-media', [AiContentController::class, 'generateSocialMediaPost'])->name('social-media');
                Route::post('/press-release', [AiContentController::class, 'generatePressRelease'])->name('press-release');
                Route::post('/email', [AiContentController::class, 'generateEmailTemplate'])->name('email');
                Route::post('/blog', [AiContentController::class, 'generateBlogPost'])->name('blog');
                Route::post('/ad-copy', [AiContentController::class, 'generateAdCopy'])->name('ad-copy');
                Route::post('/variations', [AiContentController::class, 'generateVariations'])->name('variations');
            });

            // AI Image Generation
            Route::prefix('images')->name('images.')->group(function () {
                Route::post('/generate', [AiImageController::class, 'generateImage'])->name('generate');
                Route::post('/{image}/optimize', [AiImageController::class, 'optimizeForPlatform'])->name('optimize');
                Route::post('/{image}/library', [AiImageController::class, 'addToLibrary'])->name('add-to-library');
            });

            // SEO Tools
            Route::prefix('seo')->name('seo.')->group(function () {
                Route::post('/keyword-research', [SeoController::class, 'researchKeyword'])->name('keyword-research');
                Route::post('/analyze-content', [SeoController::class, 'analyzeContent'])->name('analyze-content');
                Route::post('/meta-tags', [SeoController::class, 'generateMetaTags'])->name('meta-tags');
                Route::post('/sitemap', [SeoController::class, 'generateSitemap'])->name('sitemap');
                Route::post('/competitor', [SeoController::class, 'analyzeCompetitor'])->name('competitor');
            });

            // Label Inspiration
            Route::prefix('label-inspiration')->name('label-inspiration.')->group(function () {
                Route::post('/generate', [LabelInspirationController::class, 'generateLabels'])->name('generate');
            });

            // Product Catalog Generator
            Route::prefix('product-catalog')->name('product-catalog.')->group(function () {
                Route::post('/generate', [ProductCatalogController::class, 'generateCatalog'])->name('generate');
                Route::post('/descriptions', [ProductCatalogController::class, 'generateProductDescriptions'])->name('descriptions');
            });

            // AI Usage & Quota
            Route::prefix('usage')->name('usage.')->group(function () {
                Route::get('/stats', [AiUsageController::class, 'getUsageStats'])->name('stats');
                Route::get('/quota', [AiUsageController::class, 'getRemainingQuota'])->name('quota');
            });
        });

        // Email Marketing
        Route::prefix('email-marketing')->name('email-marketing.')->group(function () {
            // Email Campaigns
            Route::middleware('not.client')->prefix('campaigns')->name('campaigns.')->group(function () {
                Route::get('/', [EmailCampaignController::class, 'index'])->name('index');
                Route::get('/create', [EmailCampaignController::class, 'create'])->name('create');
                Route::post('/', [EmailCampaignController::class, 'store'])->name('store');
                Route::get('/{emailCampaign}', [EmailCampaignController::class, 'show'])->name('show');
                Route::put('/{emailCampaign}', [EmailCampaignController::class, 'update'])->name('update');
                Route::delete('/{emailCampaign}', [EmailCampaignController::class, 'destroy'])->name('destroy');
                Route::post('/{emailCampaign}/send', [EmailCampaignController::class, 'send'])->name('send');
                Route::post('/{emailCampaign}/schedule', [EmailCampaignController::class, 'schedule'])->name('schedule');
                Route::post('/{emailCampaign}/pause', [EmailCampaignController::class, 'pause'])->name('pause');
                Route::post('/{emailCampaign}/resume', [EmailCampaignController::class, 'resume'])->name('resume');
                Route::post('/{emailCampaign}/cancel', [EmailCampaignController::class, 'cancel'])->name('cancel');
                Route::get('/{emailCampaign}/metrics', [EmailCampaignController::class, 'getMetrics'])->name('metrics');
            });

            // Contacts
            Route::prefix('contacts')->name('contacts.')->group(function () {
                Route::get('/', [ContactController::class, 'index'])->name('index');
                Route::get('/create', [ContactController::class, 'create'])->name('create');
                Route::post('/', [ContactController::class, 'store'])->name('store');
                Route::post('/import', [ContactController::class, 'import'])->name('import');
                Route::get('/{contact}', [ContactController::class, 'show'])->name('show');
                Route::put('/{contact}', [ContactController::class, 'update'])->name('update');
                Route::delete('/{contact}', [ContactController::class, 'destroy'])->name('destroy');
                Route::get('/{contact}/duplicates', [ContactController::class, 'findDuplicates'])->name('duplicates');
                Route::post('/{contact}/merge', [ContactController::class, 'merge'])->name('merge');
                Route::post('/{contact}/subscribe', [ContactController::class, 'subscribe'])->name('subscribe');
                Route::post('/{contact}/unsubscribe', [ContactController::class, 'unsubscribe'])->name('unsubscribe');
                Route::get('/{contact}/export', [ContactController::class, 'exportData'])->name('export');
                Route::delete('/{contact}/data', [ContactController::class, 'deleteData'])->name('delete-data');
            });

            // Email Templates
            Route::middleware('not.client')->prefix('templates')->name('templates.')->group(function () {
                Route::get('/', [EmailTemplateController::class, 'index'])->name('index');
                Route::get('/builder/{emailTemplate?}', [EmailTemplateController::class, 'builder'])->name('builder');
                Route::post('/', [EmailTemplateController::class, 'store'])->name('store');
                Route::get('/{emailTemplate}', [EmailTemplateController::class, 'show'])->name('show');
                Route::put('/{emailTemplate}', [EmailTemplateController::class, 'update'])->name('update');
                Route::delete('/{emailTemplate}', [EmailTemplateController::class, 'destroy'])->name('destroy');
                Route::post('/{emailTemplate}/render', [EmailTemplateController::class, 'render'])->name('render');
            });

            // Contact Lists
            Route::prefix('contact-lists')->name('contact-lists.')->group(function () {
                Route::get('/', [ContactListController::class, 'index'])->name('index');
                Route::post('/', [ContactListController::class, 'store'])->name('store');
                Route::get('/{contactList}', [ContactListController::class, 'show'])->name('show');
                Route::put('/{contactList}', [ContactListController::class, 'update'])->name('update');
                Route::delete('/{contactList}', [ContactListController::class, 'destroy'])->name('destroy');
                Route::post('/{contactList}/contacts', [ContactListController::class, 'addContacts'])->name('add-contacts');
                Route::delete('/{contactList}/contacts', [ContactListController::class, 'removeContacts'])->name('remove-contacts');
            });
        });

        // Brand Assets Page (brand-scoped)
        Route::middleware('brand.context')->get('/brand-assets', [BrandController::class, 'brandAssets'])->name('brand-assets');

        // Files Management (brand-scoped)
        Route::middleware('brand.context')->prefix('files')->name('files.')->group(function () {
            Route::get('/', [FileController::class, 'index'])->name('index');
            Route::post('/', [FileController::class, 'store'])->name('store');
            Route::get('/{file}', [FileController::class, 'show'])->name('show');
            Route::delete('/{file}', [FileController::class, 'destroy'])->name('destroy');
            Route::get('/{file}/download', [FileController::class, 'download'])->name('download');
        });

        // Analytics (brand-scoped)
        Route::middleware('brand.context')->prefix('analytics')->name('analytics.')->group(function () {
            Route::get('/', [AnalyticsController::class, 'index'])->name('index');
            Route::post('/analyze', [AnalyticsController::class, 'analyze'])->name('analyze');
            Route::get('/reports/{reportId}', [AnalyticsController::class, 'getAnalysis'])->name('reports.show');
            Route::get('/campaigns/{campaignId}/performance', [AnalyticsController::class, 'getCampaignPerformance'])->name('campaigns.performance');
            Route::get('/social-media/engagement', [AnalyticsController::class, 'getSocialMediaEngagement'])->name('social-media.engagement');
            Route::get('/campaigns/{campaignId}/roi', [AnalyticsController::class, 'calculateROI'])->name('campaigns.roi');
            Route::get('/competitors/comparison', [AnalyticsController::class, 'getCompetitorComparison'])->name('competitors.comparison');
            Route::post('/sentiment', [AnalyticsController::class, 'analyzeSentiment'])->name('sentiment.analyze');
            Route::get('/sentiment/trends', [AnalyticsController::class, 'getSentimentTrends'])->name('sentiment.trends');
            Route::get('/sentiment/alerts', [AnalyticsController::class, 'getSentimentAlerts'])->name('sentiment.alerts');
            Route::post('/predictions/campaigns/{campaignId}', [AnalyticsController::class, 'predictCampaignPerformance'])->name('predictions.campaigns');
            Route::post('/predictions/content-engagement', [AnalyticsController::class, 'predictContentEngagement'])->name('predictions.content-engagement');
            Route::post('/predictions/campaigns/{campaignId}/roi', [AnalyticsController::class, 'predictROI'])->name('predictions.roi');
            Route::get('/predictions/optimal-posting-times', [AnalyticsController::class, 'getOptimalPostingTimes'])->name('predictions.optimal-posting-times');
            Route::get('/predictions/budget-optimization', [AnalyticsController::class, 'getBudgetOptimization'])->name('predictions.budget-optimization');
        });

        // Reports
        Route::middleware('not.client')->prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::get('/create', [ReportController::class, 'create'])->name('create');
            Route::post('/', [ReportController::class, 'store'])->name('store');
            Route::get('/{reportId}', [ReportController::class, 'show'])->name('show');
            Route::put('/{reportId}', [ReportController::class, 'update'])->name('update');
            Route::delete('/{reportId}', [ReportController::class, 'destroy'])->name('destroy');
            Route::post('/{reportId}/generate', [ReportController::class, 'generate'])->name('generate');
            Route::post('/{reportId}/schedule', [ReportController::class, 'schedule'])->name('schedule');
            Route::post('/{reportId}/share', [ReportController::class, 'share'])->name('share');
            Route::post('/{reportId}/export', [ReportController::class, 'export'])->name('export');
        });

        // Brand Management
        Route::prefix('brands')->name('brands.')->group(function () {
            Route::get('/', [BrandController::class, 'index'])->name('index');
            Route::post('/', [BrandController::class, 'store'])->name('store');
            Route::get('/create', [BrandController::class, 'create'])->name('create');
            Route::get('/choose-name', [BrandNameGeneratorController::class, 'index'])->name('choose-name');
            Route::post('/generate-concept', [BrandController::class, 'generateConcept'])->name('generate-concept');

            Route::prefix('name-generator')->name('name-generator.')->group(function () {
                Route::post('/generate', [BrandNameGeneratorController::class, 'generate'])->name('generate');
                Route::post('/check-domain', [BrandNameGeneratorController::class, 'checkDomain'])->name('check-domain');
                Route::post('/check-handles', [BrandNameGeneratorController::class, 'checkSocialHandles'])->name('check-handles');
            });

            Route::get('/{brand}/edit', [BrandController::class, 'edit'])->name('edit');
            Route::get('/{brand}', [BrandController::class, 'show'])->name('show');
            Route::put('/{brand}', [BrandController::class, 'update'])->name('update');
            Route::delete('/{brand}', [BrandController::class, 'destroy'])->name('destroy');

            Route::prefix('{brand}/assets')->name('assets.')->group(function () {
                Route::get('/', [BrandAssetController::class, 'index'])->name('index');
                Route::get('/{brandAsset}', [BrandAssetController::class, 'show'])->name('show');
                Route::post('/', [BrandAssetController::class, 'store'])->name('store');
                Route::put('/{brandAsset}', [BrandAssetController::class, 'update'])->name('update');
                Route::delete('/{brandAsset}', [BrandAssetController::class, 'destroy'])->name('destroy');
            });
        });

        // Product Management
        Route::prefix('products')->name('products.')->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('index');
            Route::get('/create', [ProductController::class, 'create'])->name('create');
            Route::post('/', [ProductController::class, 'store'])->name('store');
            Route::post('/import', [ProductController::class, 'import'])->name('import');
            Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');
            Route::get('/{product}', [ProductController::class, 'show'])->name('show');
            Route::put('/{product}', [ProductController::class, 'update'])->name('update');
            Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
            Route::put('/{product}/stock', [ProductController::class, 'updateStock'])->name('update-stock');

            // Product Images
            Route::prefix('{product}/images')->name('images.')->group(function () {
                Route::post('/', [ProductImageController::class, 'store'])->name('store');
                Route::delete('/{productImage}', [ProductImageController::class, 'destroy'])->name('destroy');
            });

            // Product Variants
            Route::prefix('{product}/variants')->name('variants.')->group(function () {
                Route::post('/', [ProductVariantController::class, 'store'])->name('store');
                Route::put('/{productVariant}', [ProductVariantController::class, 'update'])->name('update');
                Route::delete('/{productVariant}', [ProductVariantController::class, 'destroy'])->name('destroy');
            });
        });

        // Product Categories
        Route::prefix('product-categories')->name('product-categories.')->group(function () {
            Route::get('/', [ProductCategoryController::class, 'index'])->name('index');
            Route::post('/', [ProductCategoryController::class, 'store'])->name('store');
            Route::put('/{productCategory}', [ProductCategoryController::class, 'update'])->name('update');
            Route::delete('/{productCategory}', [ProductCategoryController::class, 'destroy'])->name('destroy');
        });

        // Social Media Integration
        Route::prefix('social')->name('social.')->group(function () {
            // OAuth Routes
            Route::prefix('auth')->name('auth.')->group(function () {
                Route::get('/{platform}/redirect', [SocialAuthController::class, 'redirectToProvider'])->name('redirect');
                Route::get('/{platform}/callback', [SocialAuthController::class, 'handleProviderCallback'])->name('callback');
            });

            // Connections
            Route::prefix('connections')->name('connections.')->group(function () {
                Route::get('/', [SocialConnectionController::class, 'index'])->name('index');
                Route::get('/{connection}', [SocialConnectionController::class, 'show'])->name('show');
                Route::post('/{connection}/refresh', [SocialAuthController::class, 'refresh'])->name('refresh');
                Route::post('/{connection}/check', [SocialConnectionController::class, 'checkStatus'])->name('check');
                Route::delete('/{connection}', [SocialConnectionController::class, 'destroy'])->name('destroy');
            });

            // Publishing
            Route::prefix('publishing')->name('publishing.')->group(function () {
                Route::post('/{scheduledPost}/publish', [PublishingController::class, 'publishNow'])->name('publish');
                Route::post('/{scheduledPost}/publish-multiple', [PublishingController::class, 'publishToMultiple'])->name('publish-multiple');
            });

            // Channels
            Route::prefix('channels')->name('channels.')->group(function () {
                Route::get('/', [ChannelController::class, 'index'])->name('index');
                Route::get('/create', [ChannelController::class, 'create'])->name('create');
                Route::post('/', [ChannelController::class, 'store'])->name('store');
                Route::get('/{channel}/edit', [ChannelController::class, 'edit'])->name('edit');
                Route::post('/{channel}/test', [ChannelController::class, 'testConnection'])->name('test');
                Route::get('/{channel}', [ChannelController::class, 'show'])->name('show');
                Route::put('/{channel}', [ChannelController::class, 'update'])->name('update');
                Route::delete('/{channel}', [ChannelController::class, 'destroy'])->name('destroy');
                Route::put('/{channel}/settings', [ChannelController::class, 'updateSettings'])->name('settings');
            });
        });

        // Press Release Management
        Route::middleware('not.client')->prefix('press-releases')->name('press-releases.')->group(function () {
            Route::get('/', [PressReleaseController::class, 'index'])->name('index');
            Route::post('/', [PressReleaseController::class, 'store'])->name('store');
            Route::get('/{pressRelease}', [PressReleaseController::class, 'show'])->name('show');
            Route::put('/{pressRelease}', [PressReleaseController::class, 'update'])->name('update');
            Route::delete('/{pressRelease}', [PressReleaseController::class, 'destroy'])->name('destroy');
            Route::post('/{pressRelease}/schedule', [PressReleaseController::class, 'schedule'])->name('schedule');
            Route::post('/{pressRelease}/approve', [PressReleaseController::class, 'approve'])->name('approve');
            Route::post('/{pressRelease}/distribute', [PressReleaseController::class, 'distribute'])->name('distribute');

            // Press Contacts
            Route::prefix('contacts')->name('contacts.')->group(function () {
                Route::get('/', [PressContactController::class, 'index'])->name('index');
                Route::post('/', [PressContactController::class, 'store'])->name('store');
                Route::post('/import', [PressContactController::class, 'import'])->name('import');
                Route::get('/{pressContact}', [PressContactController::class, 'show'])->name('show');
                Route::put('/{pressContact}', [PressContactController::class, 'update'])->name('update');
                Route::delete('/{pressContact}', [PressContactController::class, 'destroy'])->name('destroy');
            });
        });

        // Competitor Analysis
        Route::middleware('not.client')->prefix('competitors')->name('competitors.')->group(function () {
            Route::get('/', [CompetitorController::class, 'index'])->name('index');
            Route::post('/', [CompetitorController::class, 'store'])->name('store');
            Route::get('/{competitor}', [CompetitorController::class, 'show'])->name('show');
            Route::put('/{competitor}', [CompetitorController::class, 'update'])->name('update');
            Route::delete('/{competitor}', [CompetitorController::class, 'destroy'])->name('destroy');
            Route::post('/{competitor}/analysis', [CompetitorController::class, 'createAnalysis'])->name('analysis');
            Route::post('/{competitor}/track-post', [CompetitorController::class, 'trackPost'])->name('track-post');
            Route::post('/compare', [CompetitorController::class, 'compare'])->name('compare');
        });

        // Automation & Workflows
        Route::middleware('not.client')->prefix('workflows')->name('workflows.')->group(function () {
            Route::get('/', [WorkflowController::class, 'index'])->name('index');
            Route::get('/builder', [WorkflowController::class, 'builder'])->name('builder');
            Route::post('/', [WorkflowController::class, 'store'])->name('store');
            Route::get('/{workflow}', [WorkflowController::class, 'show'])->name('show');
            Route::put('/{workflow}', [WorkflowController::class, 'update'])->name('update');
            Route::delete('/{workflow}', [WorkflowController::class, 'destroy'])->name('destroy');
            Route::post('/{workflow}/execute', [WorkflowController::class, 'execute'])->name('execute');
        });

        // Website Chatbot
        Route::middleware('not.client')->prefix('chatbots')->name('chatbots.')->group(function () {
            Route::get('/', [ChatbotController::class, 'index'])->name('index');
            Route::get('/builder', [ChatbotController::class, 'builder'])->name('builder');
            Route::get('/deployment', [ChatbotController::class, 'deployment'])->name('deployment');
            Route::get('/analytics', [ChatbotController::class, 'analytics'])->name('analytics');
            Route::post('/', [ChatbotController::class, 'store'])->name('store');
            Route::get('/{chatbot}', [ChatbotController::class, 'show'])->name('show');
            Route::put('/{chatbot}', [ChatbotController::class, 'update'])->name('update');
            Route::delete('/{chatbot}', [ChatbotController::class, 'destroy'])->name('destroy');
            Route::post('/{chatbot}/conversation', [ChatbotController::class, 'startConversation'])->name('conversation');
            Route::post('/{chatbot}/lead', [ChatbotController::class, 'captureLead'])->name('lead');
        });

        // Landing Page Builder
        Route::middleware('not.client')->prefix('landing-pages')->name('landing-pages.')->group(function () {
            Route::get('/', [LandingPageController::class, 'index'])->name('index');
            Route::get('/create', [LandingPageController::class, 'create'])->name('create');
            Route::post('/', [LandingPageController::class, 'store'])->name('store');
            Route::get('/{landingPage}/builder', [LandingPageController::class, 'builder'])->name('builder');
            Route::get('/{landingPage}/preview', [LandingPageController::class, 'preview'])->name('preview');
            Route::get('/{landingPage}/analytics', [LandingPageController::class, 'analytics'])->name('analytics');
            Route::get('/{landingPage}', [LandingPageController::class, 'show'])->name('show');
            Route::put('/{landingPage}', [LandingPageController::class, 'update'])->name('update');
            Route::delete('/{landingPage}', [LandingPageController::class, 'destroy'])->name('destroy');
            Route::post('/{landingPage}/publish', [LandingPageController::class, 'publish'])->name('publish');
            Route::post('/{landingPage}/variants', [LandingPageController::class, 'createVariant'])->name('variants.store');
            Route::post('/{landingPage}/set-winner', [LandingPageController::class, 'setWinner'])->name('set-winner');
        });

        // Surveys & Feedback
        Route::middleware('not.client')->prefix('surveys')->name('surveys.')->group(function () {
            Route::get('/', [SurveyController::class, 'index'])->name('index');
            Route::get('/create', [SurveyController::class, 'create'])->name('create');
            Route::post('/', [SurveyController::class, 'store'])->name('store');
            Route::get('/{survey}/builder', [SurveyController::class, 'builder'])->name('builder');
            Route::get('/{survey}/analytics', [SurveyController::class, 'analytics'])->name('analytics');
            Route::get('/{survey}/export', [SurveyController::class, 'export'])->name('export');
            Route::get('/{survey}', [SurveyController::class, 'show'])->name('show');
            Route::put('/{survey}', [SurveyController::class, 'update'])->name('update');
            Route::delete('/{survey}', [SurveyController::class, 'destroy'])->name('destroy');
            Route::post('/{survey}/questions', [SurveyController::class, 'addQuestion'])->name('questions.store');
            Route::post('/{survey}/responses', [SurveyController::class, 'submitResponse'])->name('responses.store');
            Route::post('/{survey}/activate', [SurveyController::class, 'activate'])->name('activate');
            Route::post('/{survey}/close', [SurveyController::class, 'close'])->name('close');
        });

        // Organization Settings (Admin only)
        Route::middleware('organization.admin')->group(function () {
            // Settings
            Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
            Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
            Route::get('/settings/{key}', [SettingsController::class, 'getSetting'])->name('settings.get');
            Route::put('/settings/{key}', [SettingsController::class, 'updateSetting'])->name('settings.update-setting');
            Route::delete('/settings/{key}', [SettingsController::class, 'deleteSetting'])->name('settings.delete');

            // Billing
            Route::get('/billing', [BillingController::class, 'index'])->name('billing');
            Route::post('/billing/subscription', [BillingController::class, 'createSubscription'])->name('billing.subscription.create');
            Route::put('/billing/subscription/upgrade', [BillingController::class, 'upgradeSubscription'])->name('billing.subscription.upgrade');
            Route::put('/billing/subscription/cancel', [BillingController::class, 'cancelSubscription'])->name('billing.subscription.cancel');
            Route::get('/billing/invoices', [BillingController::class, 'getInvoices'])->name('billing.invoices');
            Route::get('/billing/payments', [BillingController::class, 'getPayments'])->name('billing.payments');
            Route::get('/billing/invoices/{invoice}/download', [BillingController::class, 'downloadInvoice'])->name('billing.invoices.download');
            Route::post('/billing/invoices/{invoice}/pay', [BillingController::class, 'payInvoice'])->name('billing.invoices.pay');
            Route::get('/billing/usage', [BillingController::class, 'getUsageStats'])->name('billing.usage');

            // Team
            Route::get('/team', [TeamController::class, 'index'])->name('team');
            Route::post('/team/members', [TeamController::class, 'addMember'])->name('team.members.add');
            Route::delete('/team/members/{userId}', [TeamController::class, 'removeMember'])->name('team.members.remove');
            Route::put('/team/members/{userId}/role', [TeamController::class, 'updateRole'])->name('team.members.update-role');
            Route::post('/team/invite', [TeamController::class, 'invite'])->name('team.invite');
            Route::get('/team/roles', [TeamController::class, 'getRoles'])->name('team.roles');

            // Storage Sources
            Route::get('/storage-sources', [StorageSourceController::class, 'index'])->name('storage-sources');
            Route::post('/storage-sources/connect', [StorageSourceController::class, 'connect'])->name('storage-sources.connect');
            Route::delete('/storage-sources/{provider}', [StorageSourceController::class, 'disconnect'])->name('storage-sources.disconnect');
            Route::get('/storage-sources/{provider}', [StorageSourceController::class, 'show'])->name('storage-sources.show');
            Route::put('/storage-sources/{provider}/settings', [StorageSourceController::class, 'updateSettings'])->name('storage-sources.update-settings');

            // Automations
            Route::get('/automations', [AutomationController::class, 'index'])->name('automations');
            Route::post('/automations', [AutomationController::class, 'store'])->name('automations.store');
            Route::post('/automations/{automationRule}/toggle', [AutomationController::class, 'toggle'])->name('automations.toggle');
            Route::post('/automations/{automationRule}/test', [AutomationController::class, 'test'])->name('automations.test');
        });
    });
});

Route::prefix('admin')->middleware(['auth:admin', 'role:admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('organizations', App\Http\Controllers\Admin\OrganizationController::class);

    // User Management
    Route::resource('users', UserController::class);
    Route::post('/users/{user}/assign-roles', [UserController::class, 'assignRoles'])->name('users.assign-roles');
    Route::post('/users/{user}/deactivate', [UserController::class, 'deactivate'])->name('users.deactivate');
    Route::post('/users/{user}/reactivate', [UserController::class, 'reactivate'])->name('users.reactivate');

    // Content Moderation
    Route::get('/content', [ContentController::class, 'index'])->name('content.index');
    Route::post('/content/flag/{post}', [ContentController::class, 'flag'])->name('content.flag');
    Route::post('/content/{moderation}/approve', [ContentController::class, 'approve'])->name('content.approve');
    Route::post('/content/{moderation}/reject', [ContentController::class, 'reject'])->name('content.reject');
    Route::delete('/content/{moderation}', [ContentController::class, 'destroy'])->name('content.destroy');
    Route::post('/content/flags/{flag}/review', [ContentController::class, 'reviewFlag'])->name('content.review-flag');

    // Platform Settings
    Route::get('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/update', [App\Http\Controllers\Admin\SettingsController::class, 'updateSetting'])->name('settings.update');
    Route::post('/settings/feature-flags/{featureFlag}/toggle', [App\Http\Controllers\Admin\SettingsController::class, 'toggleFeatureFlag'])->name('settings.toggle-feature-flag');
    Route::post('/settings/feature-flags', [App\Http\Controllers\Admin\SettingsController::class, 'updateFeatureFlag'])->name('settings.update-feature-flag');
    Route::post('/settings/api-keys', [App\Http\Controllers\Admin\SettingsController::class, 'updateApiKey'])->name('settings.update-api-key');
    Route::post('/settings/maintenance/enable', [App\Http\Controllers\Admin\SettingsController::class, 'enableMaintenanceMode'])->name('settings.enable-maintenance');
    Route::post('/settings/maintenance/disable', [App\Http\Controllers\Admin\SettingsController::class, 'disableMaintenanceMode'])->name('settings.disable-maintenance');

    Route::middleware('platform.super_admin')->prefix('settings/payments')->name('settings.payments.')->group(function () {
        Route::get('/', [PaymentWebhookSettingsController::class, 'index'])->name('index');
        Route::put('/', [PaymentWebhookSettingsController::class, 'update'])->name('update');
    });

    // System Logs
    Route::get('/logs', [LogController::class, 'index'])->name('logs.index');

    // Admin Analytics & Costing
    Route::get('/costing', [CostingController::class, 'index'])->name('costing.index');

    // Other admin routes (to be implemented)
    Route::resource('packages', PackageController::class);
    Route::get('/billing', [App\Http\Controllers\Admin\BillingController::class, 'index'])->name('billing.index');
    Route::get('/team', [App\Http\Controllers\Admin\TeamController::class, 'index'])->name('team.index');
});

Route::middleware(['auth', 'verified', 'role:agency'])->prefix('agency')->name('agency.')->group(function () {
    Route::middleware('agency')->prefix('{agency}')->group(function () {
        Route::get('/', [App\Http\Controllers\Agency\DashboardController::class, 'index'])->name('dashboard');

        Route::prefix('clients')->name('clients.')->group(function () {
            Route::get('/', [ClientController::class, 'index'])->name('index');
            Route::middleware('agency.admin')->get('/create', [ClientController::class, 'create'])->name('create');
            Route::middleware('agency.admin')->post('/', [ClientController::class, 'store'])->name('store');
            Route::middleware('agency.client')->get('/{organizationId}', [ClientController::class, 'show'])->name('show');
        });

        Route::get('/tasks', [App\Http\Controllers\Agency\TaskController::class, 'index'])->name('tasks');
        Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');

        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [App\Http\Controllers\Agency\ReportController::class, 'index'])->name('index');
            Route::post('/generate', [App\Http\Controllers\Agency\ReportController::class, 'generate'])->name('generate');
            Route::get('/{report}/download', [App\Http\Controllers\Agency\ReportController::class, 'download'])->name('download');
        });

        // Admin-only routes
        Route::middleware('agency.admin')->group(function () {
            Route::prefix('billing')->name('billing.')->group(function () {
                Route::get('/', [App\Http\Controllers\Agency\BillingController::class, 'index'])->name('index');
                Route::post('/{invoice}/pay', [App\Http\Controllers\Agency\BillingController::class, 'pay'])->name('pay');
                Route::get('/{invoice}/download', [App\Http\Controllers\Agency\BillingController::class, 'download'])->name('download');
                Route::post('/send-reminders', [App\Http\Controllers\Agency\BillingController::class, 'sendReminders'])->name('send-reminders');
            });

            Route::prefix('team')->name('team.')->group(function () {
                Route::get('/', [App\Http\Controllers\Agency\TeamController::class, 'index'])->name('index');
                Route::post('/', [App\Http\Controllers\Agency\TeamController::class, 'store'])->name('store');
                Route::put('/{user}', [App\Http\Controllers\Agency\TeamController::class, 'update'])->name('update');
                Route::delete('/{user}', [App\Http\Controllers\Agency\TeamController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('settings')->name('settings.')->group(function () {
                Route::get('/', [App\Http\Controllers\Agency\SettingsController::class, 'index'])->name('index');
                Route::put('/profile', [App\Http\Controllers\Agency\SettingsController::class, 'updateProfile'])->name('update-profile');
                Route::put('/branding', [App\Http\Controllers\Agency\SettingsController::class, 'updateBranding'])->name('update-branding');
                Route::put('/defaults', [App\Http\Controllers\Agency\SettingsController::class, 'updateDefaults'])->name('update-defaults');
                Route::put('/integrations', [App\Http\Controllers\Agency\SettingsController::class, 'updateIntegrations'])->name('update-integrations');
                Route::put('/notifications', [App\Http\Controllers\Agency\SettingsController::class, 'updateNotifications'])->name('update-notifications');
            });
        });
    });
});

// Webhook routes (no auth required)
Route::prefix('webhooks')->name('webhooks.')->group(function () {
    Route::post('/facebook', [WebhookController::class, 'handleFacebook'])->name('facebook');
    Route::post('/instagram', [WebhookController::class, 'handleInstagram'])->name('instagram');
    Route::post('/linkedin', [WebhookController::class, 'handleLinkedIn'])->name('linkedin');
    Route::post('/twitter', [WebhookController::class, 'handleTwitter'])->name('twitter');
    Route::post('/tiktok', [WebhookController::class, 'handleTikTok'])->name('tiktok');
    Route::post('/pinterest', [WebhookController::class, 'handlePinterest'])->name('pinterest');
    Route::post('/stripe', [PaymentWebhookController::class, 'stripe'])->name('stripe');
    Route::post('/paypal', [PaymentWebhookController::class, 'paypal'])->name('paypal');
});

Route::get('/p/{slug}', [PublicLandingPageController::class, 'show'])->name('public.landing-page');
Route::get('/s/{survey}', [PublicSurveyController::class, 'show'])->name('public.survey');
Route::post('/s/{survey}', [PublicSurveyController::class, 'submit'])->name('public.survey.submit');

// Email tracking routes (no auth required)
Route::prefix('email')->name('email.')->group(function () {
    Route::get('/track/open/{token}', [EmailTrackingController::class, 'trackOpen'])->name('track.open');
    Route::get('/track/click/{token}', [EmailTrackingController::class, 'trackClick'])->name('track.click');
    Route::get('/unsubscribe/{token}', [EmailTrackingController::class, 'unsubscribe'])->name('unsubscribe');
});
