<?php $__env->startSection('content'); ?>
<div class="space-y-4">
    <h1 class="text-2xl font-bold">Platform team</h1>
    <div class="bg-white border rounded-lg divide-y">
        <?php $__currentLoopData = $admins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $admin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="p-4 flex justify-between">
                <div>
                    <p class="font-medium"><?php echo e($admin->name); ?></p>
                    <p class="text-sm text-gray-500"><?php echo e($admin->email); ?></p>
                </div>
                <a href="<?php echo e(route('admin.users.show', $admin)); ?>" class="text-blue-700 text-sm">View user</a>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\marketingmanager-laravel\resources\views/admin/team/index.blade.php ENDPATH**/ ?>