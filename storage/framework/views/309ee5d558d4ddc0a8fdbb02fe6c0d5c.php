<?php $__env->startSection('content'); ?>
<div class="space-y-4">
    <div class="flex justify-between">
        <h1 class="text-2xl font-bold">Packages</h1>
        <a href="<?php echo e(route('admin.packages.create')); ?>" class="bg-blue-600 text-white px-4 py-2 rounded-md">New package</a>
    </div>
    <div class="bg-white border rounded-lg divide-y">
        <?php $__empty_1 = true; $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="p-4 flex justify-between">
                <div>
                    <h2 class="font-medium"><?php echo e($package->name); ?></h2>
                    <p class="text-sm text-gray-500"><?php echo e($package->billing_cycle); ?> · <?php echo e($package->price); ?> · <?php echo e($package->is_active ? 'Active' : 'Inactive'); ?></p>
                </div>
                <div class="flex gap-3 text-sm">
                    <a href="<?php echo e(route('admin.packages.edit', $package)); ?>">Edit</a>
                    <form method="POST" action="<?php echo e(route('admin.packages.destroy', $package)); ?>"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="text-red-600">Delete</button></form>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="p-8 text-center text-gray-500">No packages.</div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\marketingmanager-laravel\resources\views/admin/packages/index.blade.php ENDPATH**/ ?>