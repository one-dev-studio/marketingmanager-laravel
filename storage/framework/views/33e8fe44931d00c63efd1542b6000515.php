<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'variant' => 'default',
    'showExitAgencyView' => false,
    'showReturnToApp' => false,
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
    'variant' => 'default',
    'showExitAgencyView' => false,
    'showReturnToApp' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $user = auth()->user();
    $textClasses = match($variant) {
        'admin' => 'text-white',
        default => 'text-gray-900',
    };
?>

<div 
    x-data="{ open: false }"
    class="relative"
>
    <button
        @click="open = !open"
        class="flex items-center space-x-3 w-full p-2 rounded-lg hover:bg-gray-100 transition-colors"
    >
        <div class="w-10 h-10 rounded-full bg-primary-600 flex items-center justify-center text-white font-semibold flex-shrink-0">
            <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

        </div>
        <div class="flex-1 min-w-0 text-left <?php echo e($textClasses); ?>">
            <p class="text-sm font-medium truncate"><?php echo e($user->name); ?></p>
            <p class="text-xs opacity-75 truncate"><?php echo e($user->email); ?></p>
        </div>
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <div
        x-show="open"
        x-cloak
        @click.away="open = false"
        class="absolute bottom-full left-0 mb-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50"
    >
        <a
            href="<?php echo e(route('profile.show')); ?>"
            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
        >
            Account Settings
        </a>
        
        <?php if($showExitAgencyView): ?>
            <a
                href="<?php echo e(route('main.organizations')); ?>"
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
            >
                Exit Agency View
            </a>
        <?php endif; ?>
        
        <?php if($showReturnToApp): ?>
            <a
                href="<?php echo e(route('main.organizations')); ?>"
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
            >
                Return to App
            </a>
        <?php endif; ?>
        
        <hr class="my-1 border-gray-200">
        
        <form method="POST" action="<?php echo e(route('logout')); ?>">
            <?php echo csrf_field(); ?>
            <button
                type="submit"
                class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
            >
                Log out
            </button>
        </form>
    </div>
</div>

<?php /**PATH D:\projects\marketingmanager-laravel\resources\views/components/partials/layout/user-menu.blade.php ENDPATH**/ ?>