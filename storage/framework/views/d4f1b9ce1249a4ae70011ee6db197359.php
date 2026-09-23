<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'href' => '#',
    'icon' => null,
    'badge' => null,
    'badgeVariant' => 'secondary',
    'isActive' => false,
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
    'href' => '#',
    'icon' => null,
    'badge' => null,
    'badgeVariant' => 'secondary',
    'isActive' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $activeClasses = $isActive 
        ? 'bg-primary-50 text-primary-700 border-r-2 border-primary-600' 
        : 'text-gray-700 hover:bg-gray-100';
    
    $badgeColors = [
        'danger' => 'bg-red-500 text-white',
        'secondary' => 'bg-gray-200 text-gray-700',
    ];
    
    $badgeClass = $badgeColors[$badgeVariant] ?? $badgeColors['secondary'];
?>

<a 
    href="<?php echo e($href); ?>"
    class="flex items-center px-3 py-2 rounded-lg transition-colors <?php echo e($activeClasses); ?>"
    <?php if($isActive): ?> aria-current="page" <?php endif; ?>
>
    <?php if($icon): ?>
        <span class="flex-shrink-0 mr-3">
            <?php echo $icon; ?>

        </span>
    <?php endif; ?>
    
    <span class="flex-1">
        <?php echo e($slot); ?>

    </span>
    
    <?php if($badge): ?>
        <span class="ml-auto <?php echo e($badgeClass); ?> text-xs font-semibold px-2 py-0.5 rounded-full">
            <?php echo e($badge); ?>

        </span>
    <?php endif; ?>
</a>
<?php /**PATH D:\projects\marketingmanager-laravel\resources\views/components/partials/layout/sidebar-menu-item.blade.php ENDPATH**/ ?>