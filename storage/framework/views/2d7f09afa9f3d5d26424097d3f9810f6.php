<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'brands' => collect(),
    'selectedBrandId' => null,
    'organizationId' => null,
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
    'brands' => collect(),
    'selectedBrandId' => null,
    'organizationId' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $currentBrand = $selectedBrandId ? $brands->firstWhere('id', $selectedBrandId) : null;
?>

<div 
    x-data="{ open: false }"
    class="w-full"
>
    <button
        @click="open = !open"
        class="w-full flex items-center justify-between px-3 py-2 rounded-lg hover:bg-gray-100 text-gray-700 transition-colors"
    >
        <div class="flex items-center flex-1 min-w-0">
            <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
            </svg>
            <span class="flex-1 text-left truncate">
                <?php echo e($currentBrand ? $currentBrand->name : 'Select Brand'); ?>

            </span>
        </div>
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <div
        x-show="open"
        x-cloak
        @click.away="open = false"
        class="mt-2 bg-white rounded-lg shadow-lg border border-gray-200 py-1 max-h-64 overflow-y-auto z-50"
    >
        <?php if($brands->isEmpty()): ?>
            <div class="px-4 py-2 text-sm text-gray-500">
                No brands available
            </div>
        <?php else: ?>
            <a
                href="<?php echo e(route('main.dashboard', ['organizationId' => $organizationId])); ?>"
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 <?php echo e(!$selectedBrandId ? 'bg-primary-50 text-primary-700' : ''); ?>"
            >
                All Brands
            </a>
            <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a
                    href="<?php echo e(route('main.dashboard', ['organizationId' => $organizationId, 'brandId' => $brand->id])); ?>"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 <?php echo e($selectedBrandId == $brand->id ? 'bg-primary-50 text-primary-700' : ''); ?>"
                >
                    <?php echo e($brand->name); ?>

                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
    </div>
</div>

<?php /**PATH D:\projects\marketingmanager-laravel\resources\views/components/partials/layout/brand-switcher.blade.php ENDPATH**/ ?>