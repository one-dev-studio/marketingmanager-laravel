<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['variant' => 'default']));

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

foreach (array_filter((['variant' => 'default']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $footerClasses = match($variant) {
        'default' => 'border-t border-gray-200',
        'agency' => 'border-t border-gray-200',
        'admin' => 'border-t border-gray-700',
        default => 'border-t border-gray-200',
    };
?>

<div class="p-4 <?php echo e($footerClasses); ?>">
    <?php echo e($slot); ?>

</div>

<?php /**PATH D:\projects\marketingmanager-laravel\resources\views/components/partials/layout/sidebar-footer.blade.php ENDPATH**/ ?>