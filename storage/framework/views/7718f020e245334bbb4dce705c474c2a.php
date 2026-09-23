<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title' => 'MarketPulse',
    'showMobileMenuToggle' => true,
    'showOrganizationSwitcher' => false,
    'showCalendarDialog' => false,
    'showReviewIndicator' => false,
    'showNotifications' => false,
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'title' => 'MarketPulse',
    'showMobileMenuToggle' => true,
    'showOrganizationSwitcher' => false,
    'showCalendarDialog' => false,
    'showReviewIndicator' => false,
    'showNotifications' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<header class="sticky top-0 z-20 bg-white border-b border-gray-200">
    <div class="px-4 md:px-6 py-4 flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <?php if($showMobileMenuToggle): ?>
                <button 
                    x-on:click="$dispatch('mobile-menu-toggle')"
                    class="md:hidden p-2 rounded-lg hover:bg-gray-100 text-gray-600"
                    aria-label="Toggle menu"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            <?php endif; ?>
            
            <h1 class="text-xl font-semibold text-gray-900">
                <?php echo e($title); ?>

            </h1>
        </div>

        <div class="flex items-center space-x-2 md:space-x-4">
            <?php if($showOrganizationSwitcher): ?>
                <?php echo $__env->make('partials.layout.organization-switcher', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>

            <?php if($showCalendarDialog): ?>
                <?php
                    $organizationId = request()->route('organizationId');
                ?>
                <?php if (isset($component)) { $__componentOriginal6728b14c7e98e6ef02945aa0448f0aa5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6728b14c7e98e6ef02945aa0448f0aa5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.calendar-dialog','data' => ['organizationId' => $organizationId]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('calendar-dialog'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['organizationId' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($organizationId)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6728b14c7e98e6ef02945aa0448f0aa5)): ?>
<?php $attributes = $__attributesOriginal6728b14c7e98e6ef02945aa0448f0aa5; ?>
<?php unset($__attributesOriginal6728b14c7e98e6ef02945aa0448f0aa5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6728b14c7e98e6ef02945aa0448f0aa5)): ?>
<?php $component = $__componentOriginal6728b14c7e98e6ef02945aa0448f0aa5; ?>
<?php unset($__componentOriginal6728b14c7e98e6ef02945aa0448f0aa5); ?>
<?php endif; ?>
            <?php endif; ?>

            <?php if($showReviewIndicator): ?>
                <?php echo $__env->make('partials.layout.review-indicator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>

            <?php if($showNotifications): ?>
                <?php echo $__env->make('partials.layout.notifications', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>

            <?php if (isset($component)) { $__componentOriginal3f27bb0470b8bf24620b9cccf200ce32 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3f27bb0470b8bf24620b9cccf200ce32 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.locale-selector','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('locale-selector'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3f27bb0470b8bf24620b9cccf200ce32)): ?>
<?php $attributes = $__attributesOriginal3f27bb0470b8bf24620b9cccf200ce32; ?>
<?php unset($__attributesOriginal3f27bb0470b8bf24620b9cccf200ce32); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3f27bb0470b8bf24620b9cccf200ce32)): ?>
<?php $component = $__componentOriginal3f27bb0470b8bf24620b9cccf200ce32; ?>
<?php unset($__componentOriginal3f27bb0470b8bf24620b9cccf200ce32); ?>
<?php endif; ?>
        </div>
    </div>
</header>

<?php /**PATH D:\projects\marketingmanager-laravel\resources\views/components/partials/layout/header.blade.php ENDPATH**/ ?>