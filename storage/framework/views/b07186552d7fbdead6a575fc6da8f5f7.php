<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['variant' => 'default', 'collapsed' => false]));

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

foreach (array_filter((['variant' => 'default', 'collapsed' => false]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $sidebarClasses = match($variant) {
        'default' => 'bg-white',
        'agency' => 'bg-white',
        'admin' => 'bg-gray-900',
        default => 'bg-white',
    };
    
    $textClasses = match($variant) {
        'admin' => 'text-white',
        default => 'text-gray-900',
    };
?>

<aside 
    x-data="{ 
        collapsed: <?php echo \Illuminate\Support\Js::from($collapsed)->toHtml() ?>,
        mobileOpen: false,
        toggle() { this.collapsed = !this.collapsed; },
        openMobile() { this.mobileOpen = true; },
        closeMobile() { this.mobileOpen = false; }
    }"
    :class="{ 'w-16': collapsed, 'w-64': !collapsed }"
    class="fixed left-0 top-0 h-screen <?php echo e($sidebarClasses); ?> shadow-lg z-40 transition-all duration-300 ease-in-out hidden md:block"
    x-on:mobile-menu-toggle.window="openMobile()"
>
    <?php echo e($slot); ?>

</aside>


<div 
    x-data="{ mobileOpen: false }"
    x-on:mobile-menu-toggle.window="mobileOpen = !mobileOpen"
    x-show="mobileOpen"
    x-cloak
    class="fixed inset-0 bg-black bg-opacity-50 z-30 md:hidden"
    x-on:click="mobileOpen = false"
    style="display: none;"
></div>


<aside 
    x-data="{ mobileOpen: false }"
    x-on:mobile-menu-toggle.window="mobileOpen = !mobileOpen"
    x-show="mobileOpen"
    x-cloak
    class="fixed left-0 top-0 h-screen w-64 <?php echo e($sidebarClasses); ?> shadow-lg z-50 md:hidden transition-transform duration-300"
    :class="{ '-translate-x-full': !mobileOpen, 'translate-x-0': mobileOpen }"
>
    <?php echo e($slot); ?>

</aside>

<?php /**PATH D:\projects\marketingmanager-laravel\resources\views/components/partials/layout/sidebar.blade.php ENDPATH**/ ?>