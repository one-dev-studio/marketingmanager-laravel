

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
        <p class="mt-1 text-gray-600">Welcome back, <?php echo e(auth('admin')->user()->name); ?>.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="rounded-lg border border-gray-200 p-4">
            <p class="text-sm text-gray-500">Organizations</p>
            <p class="text-2xl font-semibold text-gray-900"><?php echo e(\App\Models\Organization::count()); ?></p>
        </div>
        <div class="rounded-lg border border-gray-200 p-4">
            <p class="text-sm text-gray-500">Users</p>
            <p class="text-2xl font-semibold text-gray-900"><?php echo e(\App\Models\User::count()); ?></p>
        </div>
        <div class="rounded-lg border border-gray-200 p-4">
            <p class="text-sm text-gray-500">Campaigns</p>
            <p class="text-2xl font-semibold text-gray-900"><?php echo e(\App\Models\Campaign::count()); ?></p>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\marketingmanager-laravel\resources\views/admin/dashboard/index.blade.php ENDPATH**/ ?>