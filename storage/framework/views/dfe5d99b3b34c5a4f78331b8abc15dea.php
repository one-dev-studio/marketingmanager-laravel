<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(isset($title) ? $title . ' - ' : ''); ?>Admin - MarketPulse</title>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="bg-gray-900 antialiased">
    <div 
        id="app" 
        class="min-h-screen flex"
        x-data="{ sidebarCollapsed: false }"
    >
        <?php
            $pageTitle = $title ?? 'Admin Dashboard';
        ?>

        <?php echo $__env->make('partials.layout.admin-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <?php if (isset($component)) { $__componentOriginalcaace722e7fb7364e81961483357321a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcaace722e7fb7364e81961483357321a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.partials.layout.sidebar-inset','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('partials.layout.sidebar-inset'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
            <?php if (isset($component)) { $__componentOriginal39a1839707a1b4e1f8d942bd7c2987dd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal39a1839707a1b4e1f8d942bd7c2987dd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.partials.layout.header','data' => ['title' => $pageTitle,'showMobileMenuToggle' => true,'showOrganizationSwitcher' => false,'showCalendarDialog' => false,'showReviewIndicator' => false,'showNotifications' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('partials.layout.header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($pageTitle),'showMobileMenuToggle' => true,'showOrganizationSwitcher' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'showCalendarDialog' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'showReviewIndicator' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'showNotifications' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal39a1839707a1b4e1f8d942bd7c2987dd)): ?>
<?php $attributes = $__attributesOriginal39a1839707a1b4e1f8d942bd7c2987dd; ?>
<?php unset($__attributesOriginal39a1839707a1b4e1f8d942bd7c2987dd); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal39a1839707a1b4e1f8d942bd7c2987dd)): ?>
<?php $component = $__componentOriginal39a1839707a1b4e1f8d942bd7c2987dd; ?>
<?php unset($__componentOriginal39a1839707a1b4e1f8d942bd7c2987dd); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginal478f7fe5913d90e74ccc12e6eb904732 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal478f7fe5913d90e74ccc12e6eb904732 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.partials.layout.main','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('partials.layout.main'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                <div class="bg-white rounded-lg shadow p-6">
                    <?php echo $__env->yieldContent('content'); ?>
                </div>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal478f7fe5913d90e74ccc12e6eb904732)): ?>
<?php $attributes = $__attributesOriginal478f7fe5913d90e74ccc12e6eb904732; ?>
<?php unset($__attributesOriginal478f7fe5913d90e74ccc12e6eb904732); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal478f7fe5913d90e74ccc12e6eb904732)): ?>
<?php $component = $__componentOriginal478f7fe5913d90e74ccc12e6eb904732; ?>
<?php unset($__componentOriginal478f7fe5913d90e74ccc12e6eb904732); ?>
<?php endif; ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcaace722e7fb7364e81961483357321a)): ?>
<?php $attributes = $__attributesOriginalcaace722e7fb7364e81961483357321a; ?>
<?php unset($__attributesOriginalcaace722e7fb7364e81961483357321a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcaace722e7fb7364e81961483357321a)): ?>
<?php $component = $__componentOriginalcaace722e7fb7364e81961483357321a; ?>
<?php unset($__componentOriginalcaace722e7fb7364e81961483357321a); ?>
<?php endif; ?>
    </div>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>

<?php /**PATH D:\projects\marketingmanager-laravel\resources\views/layouts/admin.blade.php ENDPATH**/ ?>