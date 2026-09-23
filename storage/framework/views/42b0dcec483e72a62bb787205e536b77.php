<?php
    $icon = function($name) {
        $icons = [
            'dashboard' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>',
            'organizations' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>',
            'users' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>',
            'content' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>',
            'packages' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>',
            'costing' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>',
            'billing' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>',
            'agency-team' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>',
            'logs' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>',
            'settings' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>',
        ];
        return $icons[$name] ?? '';
    };
?>

<?php if (isset($component)) { $__componentOriginalf53dbec82011db9166838c8709ec7dc3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf53dbec82011db9166838c8709ec7dc3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.partials.layout.sidebar','data' => ['variant' => 'admin']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('partials.layout.sidebar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'admin']); ?>
    <?php if (isset($component)) { $__componentOriginal34492e17f0404d9f436f18cfed3833a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal34492e17f0404d9f436f18cfed3833a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.partials.layout.sidebar-header','data' => ['variant' => 'admin']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('partials.layout.sidebar-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'admin']); ?>
        <div class="flex items-center space-x-2">
            <svg class="w-6 h-6 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
            </svg>
            <span class="text-lg font-semibold text-white">Admin Panel</span>
        </div>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal34492e17f0404d9f436f18cfed3833a5)): ?>
<?php $attributes = $__attributesOriginal34492e17f0404d9f436f18cfed3833a5; ?>
<?php unset($__attributesOriginal34492e17f0404d9f436f18cfed3833a5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal34492e17f0404d9f436f18cfed3833a5)): ?>
<?php $component = $__componentOriginal34492e17f0404d9f436f18cfed3833a5; ?>
<?php unset($__componentOriginal34492e17f0404d9f436f18cfed3833a5); ?>
<?php endif; ?>

    <?php if (isset($component)) { $__componentOriginal76222d8b88f2f40a6d1dd458286c268d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal76222d8b88f2f40a6d1dd458286c268d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.partials.layout.sidebar-content','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('partials.layout.sidebar-content'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
        <?php if (isset($component)) { $__componentOriginaled4c3e076b3f6648d05cc07133c68008 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaled4c3e076b3f6648d05cc07133c68008 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.partials.layout.sidebar-menu','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('partials.layout.sidebar-menu'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
            <?php if (isset($component)) { $__componentOriginala2a12bcb3bf604d6858623ff0ed805e8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala2a12bcb3bf604d6858623ff0ed805e8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.partials.layout.sidebar-menu-item','data' => ['href' => ''.e(route('admin.dashboard')).'','icon' => $icon('dashboard'),'isActive' => request()->routeIs('admin.dashboard')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('partials.layout.sidebar-menu-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('admin.dashboard')).'','icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($icon('dashboard')),'is-active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('admin.dashboard'))]); ?>
                Dashboard
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala2a12bcb3bf604d6858623ff0ed805e8)): ?>
<?php $attributes = $__attributesOriginala2a12bcb3bf604d6858623ff0ed805e8; ?>
<?php unset($__attributesOriginala2a12bcb3bf604d6858623ff0ed805e8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala2a12bcb3bf604d6858623ff0ed805e8)): ?>
<?php $component = $__componentOriginala2a12bcb3bf604d6858623ff0ed805e8; ?>
<?php unset($__componentOriginala2a12bcb3bf604d6858623ff0ed805e8); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginala2a12bcb3bf604d6858623ff0ed805e8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala2a12bcb3bf604d6858623ff0ed805e8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.partials.layout.sidebar-menu-item','data' => ['href' => ''.e(route('admin.organizations.index')).'','icon' => $icon('organizations'),'isActive' => request()->routeIs('admin.organizations.*')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('partials.layout.sidebar-menu-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('admin.organizations.index')).'','icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($icon('organizations')),'is-active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('admin.organizations.*'))]); ?>
                Organizations
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala2a12bcb3bf604d6858623ff0ed805e8)): ?>
<?php $attributes = $__attributesOriginala2a12bcb3bf604d6858623ff0ed805e8; ?>
<?php unset($__attributesOriginala2a12bcb3bf604d6858623ff0ed805e8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala2a12bcb3bf604d6858623ff0ed805e8)): ?>
<?php $component = $__componentOriginala2a12bcb3bf604d6858623ff0ed805e8; ?>
<?php unset($__componentOriginala2a12bcb3bf604d6858623ff0ed805e8); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginala2a12bcb3bf604d6858623ff0ed805e8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala2a12bcb3bf604d6858623ff0ed805e8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.partials.layout.sidebar-menu-item','data' => ['href' => ''.e(route('admin.users.index')).'','icon' => $icon('users'),'isActive' => request()->routeIs('admin.users.*')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('partials.layout.sidebar-menu-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('admin.users.index')).'','icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($icon('users')),'is-active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('admin.users.*'))]); ?>
                Users
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala2a12bcb3bf604d6858623ff0ed805e8)): ?>
<?php $attributes = $__attributesOriginala2a12bcb3bf604d6858623ff0ed805e8; ?>
<?php unset($__attributesOriginala2a12bcb3bf604d6858623ff0ed805e8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala2a12bcb3bf604d6858623ff0ed805e8)): ?>
<?php $component = $__componentOriginala2a12bcb3bf604d6858623ff0ed805e8; ?>
<?php unset($__componentOriginala2a12bcb3bf604d6858623ff0ed805e8); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginala2a12bcb3bf604d6858623ff0ed805e8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala2a12bcb3bf604d6858623ff0ed805e8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.partials.layout.sidebar-menu-item','data' => ['href' => ''.e(route('admin.content.index')).'','icon' => $icon('content'),'isActive' => request()->routeIs('admin.content.*')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('partials.layout.sidebar-menu-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('admin.content.index')).'','icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($icon('content')),'is-active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('admin.content.*'))]); ?>
                Content
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala2a12bcb3bf604d6858623ff0ed805e8)): ?>
<?php $attributes = $__attributesOriginala2a12bcb3bf604d6858623ff0ed805e8; ?>
<?php unset($__attributesOriginala2a12bcb3bf604d6858623ff0ed805e8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala2a12bcb3bf604d6858623ff0ed805e8)): ?>
<?php $component = $__componentOriginala2a12bcb3bf604d6858623ff0ed805e8; ?>
<?php unset($__componentOriginala2a12bcb3bf604d6858623ff0ed805e8); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginala2a12bcb3bf604d6858623ff0ed805e8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala2a12bcb3bf604d6858623ff0ed805e8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.partials.layout.sidebar-menu-item','data' => ['href' => ''.e(route('admin.packages.index')).'','icon' => $icon('packages'),'isActive' => request()->routeIs('admin.packages.*')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('partials.layout.sidebar-menu-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('admin.packages.index')).'','icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($icon('packages')),'is-active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('admin.packages.*'))]); ?>
                Packages
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala2a12bcb3bf604d6858623ff0ed805e8)): ?>
<?php $attributes = $__attributesOriginala2a12bcb3bf604d6858623ff0ed805e8; ?>
<?php unset($__attributesOriginala2a12bcb3bf604d6858623ff0ed805e8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala2a12bcb3bf604d6858623ff0ed805e8)): ?>
<?php $component = $__componentOriginala2a12bcb3bf604d6858623ff0ed805e8; ?>
<?php unset($__componentOriginala2a12bcb3bf604d6858623ff0ed805e8); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginala2a12bcb3bf604d6858623ff0ed805e8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala2a12bcb3bf604d6858623ff0ed805e8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.partials.layout.sidebar-menu-item','data' => ['href' => ''.e(route('admin.costing.index')).'','icon' => $icon('costing'),'isActive' => request()->routeIs('admin.costing.*')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('partials.layout.sidebar-menu-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('admin.costing.index')).'','icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($icon('costing')),'is-active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('admin.costing.*'))]); ?>
                Costing
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala2a12bcb3bf604d6858623ff0ed805e8)): ?>
<?php $attributes = $__attributesOriginala2a12bcb3bf604d6858623ff0ed805e8; ?>
<?php unset($__attributesOriginala2a12bcb3bf604d6858623ff0ed805e8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala2a12bcb3bf604d6858623ff0ed805e8)): ?>
<?php $component = $__componentOriginala2a12bcb3bf604d6858623ff0ed805e8; ?>
<?php unset($__componentOriginala2a12bcb3bf604d6858623ff0ed805e8); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginala2a12bcb3bf604d6858623ff0ed805e8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala2a12bcb3bf604d6858623ff0ed805e8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.partials.layout.sidebar-menu-item','data' => ['href' => ''.e(route('admin.billing.index')).'','icon' => $icon('billing'),'isActive' => request()->routeIs('admin.billing.*')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('partials.layout.sidebar-menu-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('admin.billing.index')).'','icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($icon('billing')),'is-active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('admin.billing.*'))]); ?>
                Billing
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala2a12bcb3bf604d6858623ff0ed805e8)): ?>
<?php $attributes = $__attributesOriginala2a12bcb3bf604d6858623ff0ed805e8; ?>
<?php unset($__attributesOriginala2a12bcb3bf604d6858623ff0ed805e8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala2a12bcb3bf604d6858623ff0ed805e8)): ?>
<?php $component = $__componentOriginala2a12bcb3bf604d6858623ff0ed805e8; ?>
<?php unset($__componentOriginala2a12bcb3bf604d6858623ff0ed805e8); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginala2a12bcb3bf604d6858623ff0ed805e8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala2a12bcb3bf604d6858623ff0ed805e8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.partials.layout.sidebar-menu-item','data' => ['href' => ''.e(route('admin.team.index')).'','icon' => $icon('agency-team'),'isActive' => request()->routeIs('admin.agency-team.*')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('partials.layout.sidebar-menu-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('admin.team.index')).'','icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($icon('agency-team')),'is-active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('admin.agency-team.*'))]); ?>
                Agency Team
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala2a12bcb3bf604d6858623ff0ed805e8)): ?>
<?php $attributes = $__attributesOriginala2a12bcb3bf604d6858623ff0ed805e8; ?>
<?php unset($__attributesOriginala2a12bcb3bf604d6858623ff0ed805e8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala2a12bcb3bf604d6858623ff0ed805e8)): ?>
<?php $component = $__componentOriginala2a12bcb3bf604d6858623ff0ed805e8; ?>
<?php unset($__componentOriginala2a12bcb3bf604d6858623ff0ed805e8); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginala2a12bcb3bf604d6858623ff0ed805e8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala2a12bcb3bf604d6858623ff0ed805e8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.partials.layout.sidebar-menu-item','data' => ['href' => ''.e(route('admin.logs.index')).'','icon' => $icon('logs'),'isActive' => request()->routeIs('admin.logs.*')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('partials.layout.sidebar-menu-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('admin.logs.index')).'','icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($icon('logs')),'is-active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('admin.logs.*'))]); ?>
                System Logs
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala2a12bcb3bf604d6858623ff0ed805e8)): ?>
<?php $attributes = $__attributesOriginala2a12bcb3bf604d6858623ff0ed805e8; ?>
<?php unset($__attributesOriginala2a12bcb3bf604d6858623ff0ed805e8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala2a12bcb3bf604d6858623ff0ed805e8)): ?>
<?php $component = $__componentOriginala2a12bcb3bf604d6858623ff0ed805e8; ?>
<?php unset($__componentOriginala2a12bcb3bf604d6858623ff0ed805e8); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginala2a12bcb3bf604d6858623ff0ed805e8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala2a12bcb3bf604d6858623ff0ed805e8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.partials.layout.sidebar-menu-item','data' => ['href' => ''.e(route('admin.settings.index')).'','icon' => $icon('settings'),'isActive' => request()->routeIs('admin.settings.*')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('partials.layout.sidebar-menu-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('admin.settings.index')).'','icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($icon('settings')),'is-active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('admin.settings.*'))]); ?>
                Platform Settings
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala2a12bcb3bf604d6858623ff0ed805e8)): ?>
<?php $attributes = $__attributesOriginala2a12bcb3bf604d6858623ff0ed805e8; ?>
<?php unset($__attributesOriginala2a12bcb3bf604d6858623ff0ed805e8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala2a12bcb3bf604d6858623ff0ed805e8)): ?>
<?php $component = $__componentOriginala2a12bcb3bf604d6858623ff0ed805e8; ?>
<?php unset($__componentOriginala2a12bcb3bf604d6858623ff0ed805e8); ?>
<?php endif; ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaled4c3e076b3f6648d05cc07133c68008)): ?>
<?php $attributes = $__attributesOriginaled4c3e076b3f6648d05cc07133c68008; ?>
<?php unset($__attributesOriginaled4c3e076b3f6648d05cc07133c68008); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaled4c3e076b3f6648d05cc07133c68008)): ?>
<?php $component = $__componentOriginaled4c3e076b3f6648d05cc07133c68008; ?>
<?php unset($__componentOriginaled4c3e076b3f6648d05cc07133c68008); ?>
<?php endif; ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal76222d8b88f2f40a6d1dd458286c268d)): ?>
<?php $attributes = $__attributesOriginal76222d8b88f2f40a6d1dd458286c268d; ?>
<?php unset($__attributesOriginal76222d8b88f2f40a6d1dd458286c268d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal76222d8b88f2f40a6d1dd458286c268d)): ?>
<?php $component = $__componentOriginal76222d8b88f2f40a6d1dd458286c268d; ?>
<?php unset($__componentOriginal76222d8b88f2f40a6d1dd458286c268d); ?>
<?php endif; ?>

    <?php if (isset($component)) { $__componentOriginal2fa78850d4e74aa2f229847151ac08b6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2fa78850d4e74aa2f229847151ac08b6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.partials.layout.sidebar-footer','data' => ['variant' => 'admin']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('partials.layout.sidebar-footer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'admin']); ?>
        <div class="flex items-center justify-between mb-2">
            <button
                x-on:click="$parent.collapsed = !$parent.collapsed"
                class="p-2 rounded-lg hover:bg-gray-800 text-gray-300"
                aria-label="Toggle sidebar"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                </svg>
            </button>
        </div>
        <?php if (isset($component)) { $__componentOriginal16891833786abe58486183d68d2b1354 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal16891833786abe58486183d68d2b1354 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.partials.layout.user-menu','data' => ['variant' => 'admin','showReturnToApp' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('partials.layout.user-menu'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'admin','showReturnToApp' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal16891833786abe58486183d68d2b1354)): ?>
<?php $attributes = $__attributesOriginal16891833786abe58486183d68d2b1354; ?>
<?php unset($__attributesOriginal16891833786abe58486183d68d2b1354); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal16891833786abe58486183d68d2b1354)): ?>
<?php $component = $__componentOriginal16891833786abe58486183d68d2b1354; ?>
<?php unset($__componentOriginal16891833786abe58486183d68d2b1354); ?>
<?php endif; ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2fa78850d4e74aa2f229847151ac08b6)): ?>
<?php $attributes = $__attributesOriginal2fa78850d4e74aa2f229847151ac08b6; ?>
<?php unset($__attributesOriginal2fa78850d4e74aa2f229847151ac08b6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2fa78850d4e74aa2f229847151ac08b6)): ?>
<?php $component = $__componentOriginal2fa78850d4e74aa2f229847151ac08b6; ?>
<?php unset($__componentOriginal2fa78850d4e74aa2f229847151ac08b6); ?>
<?php endif; ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf53dbec82011db9166838c8709ec7dc3)): ?>
<?php $attributes = $__attributesOriginalf53dbec82011db9166838c8709ec7dc3; ?>
<?php unset($__attributesOriginalf53dbec82011db9166838c8709ec7dc3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf53dbec82011db9166838c8709ec7dc3)): ?>
<?php $component = $__componentOriginalf53dbec82011db9166838c8709ec7dc3; ?>
<?php unset($__componentOriginalf53dbec82011db9166838c8709ec7dc3); ?>
<?php endif; ?>

<?php /**PATH D:\projects\marketingmanager-laravel\resources\views/partials/layout/admin-sidebar.blade.php ENDPATH**/ ?>