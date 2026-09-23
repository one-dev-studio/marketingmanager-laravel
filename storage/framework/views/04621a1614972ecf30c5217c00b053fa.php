<?php $__env->startSection('page-title', $brand->name); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6 max-w-3xl">
    <div class="flex items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900"><?php echo e($brand->name); ?></h1>
            <p class="mt-1 text-sm text-gray-600"><?php echo e($brand->status); ?></p>
        </div>
        <div class="flex items-center gap-3">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $brand)): ?>
                <a href="<?php echo e(route('main.brands.edit', ['organizationId' => $organizationId, 'brand' => $brand])); ?>"
                   class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Edit</a>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $brand)): ?>
                <form method="POST"
                      action="<?php echo e(route('main.brands.destroy', ['organizationId' => $organizationId, 'brand' => $brand])); ?>"
                      onsubmit="return confirm('Delete this brand? This cannot be undone.');">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 p-6 space-y-4">
        <div>
            <h2 class="text-sm font-medium text-gray-500">Summary</h2>
            <p class="mt-1 text-gray-900 whitespace-pre-wrap"><?php echo e($brand->summary ?: '—'); ?></p>
        </div>
        <div>
            <h2 class="text-sm font-medium text-gray-500">Guidelines</h2>
            <p class="mt-1 text-gray-900 whitespace-pre-wrap"><?php echo e($brand->guidelines ?: '—'); ?></p>
        </div>
        <div>
            <h2 class="text-sm font-medium text-gray-500">Tone of voice</h2>
            <p class="mt-1 text-gray-900"><?php echo e($brand->tone_of_voice ?: '—'); ?></p>
        </div>
        <div>
            <h2 class="text-sm font-medium text-gray-500">Audience</h2>
            <p class="mt-1 text-gray-900 whitespace-pre-wrap"><?php echo e($brand->audience ?: '—'); ?></p>
        </div>
        <div>
            <h2 class="text-sm font-medium text-gray-500">Keywords to use</h2>
            <p class="mt-1 text-gray-900"><?php echo e(implode(', ', $brand->keywords ?? []) ?: '—'); ?></p>
        </div>
        <div>
            <h2 class="text-sm font-medium text-gray-500">Keywords to avoid</h2>
            <p class="mt-1 text-gray-900"><?php echo e(implode(', ', $brand->avoid_keywords ?? []) ?: '—'); ?></p>
        </div>
    </div>

    <a href="<?php echo e(route('main.brands.index', ['organizationId' => $organizationId])); ?>" class="text-sm text-blue-600 hover:text-blue-800">Back to brands</a>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\marketingmanager-laravel\resources\views/brands/show.blade.php ENDPATH**/ ?>