<?php $__env->startSection('page-title', 'Brands'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Brands</h1>
            <p class="mt-1 text-sm text-gray-600">Manage brand guidelines, tone of voice, and keywords</p>
        </div>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Models\Brand::class)): ?>
            <div class="flex items-center gap-3">
                <a href="<?php echo e(route('main.brands.choose-name', ['organizationId' => $organizationId])); ?>"
                   class="border border-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-50">
                    Choose name
                </a>
                <a href="<?php echo e(route('main.brands.create', ['organizationId' => $organizationId])); ?>"
                   class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    Add Brand
                </a>
            </div>
        <?php endif; ?>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <?php $__empty_1 = true; $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="p-4 border-b border-gray-100 flex items-start justify-between gap-4 hover:bg-gray-50">
                <div class="min-w-0">
                    <a href="<?php echo e(route('main.brands.show', ['organizationId' => $organizationId, 'brand' => $brand])); ?>"
                       class="font-medium text-gray-900 hover:text-blue-700"><?php echo e($brand->name); ?></a>
                    <p class="text-sm text-gray-500 mt-1">
                        <?php echo e($brand->status); ?>

                        <?php if($brand->summary): ?>
                            · <?php echo e(\Illuminate\Support\Str::limit($brand->summary, 120)); ?>

                        <?php endif; ?>
                    </p>
                    <?php if($brand->guidelines): ?>
                        <p class="text-sm text-gray-400 mt-1"><?php echo e(\Illuminate\Support\Str::limit($brand->guidelines, 160)); ?></p>
                    <?php endif; ?>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $brand)): ?>
                        <a href="<?php echo e(route('main.brands.edit', ['organizationId' => $organizationId, 'brand' => $brand])); ?>"
                           class="text-blue-600 hover:text-blue-800 text-sm">Edit</a>
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
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="p-8 text-center text-gray-500">No brands yet.</div>
        <?php endif; ?>
    </div>

    <?php echo e($brands->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\marketingmanager-laravel\resources\views/brands/index.blade.php ENDPATH**/ ?>