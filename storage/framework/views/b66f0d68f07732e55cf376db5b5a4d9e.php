

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Platform Settings</h1>
        <p class="mt-1 text-gray-600">Configure global platform settings and feature flags</p>
    </div>

    <div class="rounded-lg border border-gray-200 p-4 space-y-2">
        <p class="text-sm text-gray-500">Maintenance Mode</p>
        <p class="text-lg font-semibold text-gray-900"><?php echo e($maintenanceMode ? 'Enabled' : 'Disabled'); ?></p>
        <?php if($maintenanceMode): ?>
            <form method="POST" action="<?php echo e(route('admin.settings.disable-maintenance')); ?>"><?php echo csrf_field(); ?><button class="text-sm text-blue-700">Disable</button></form>
        <?php else: ?>
            <form method="POST" action="<?php echo e(route('admin.settings.enable-maintenance')); ?>"><?php echo csrf_field(); ?><button class="text-sm text-blue-700">Enable</button></form>
        <?php endif; ?>
    </div>
    <form method="POST" action="<?php echo e(route('admin.settings.update')); ?>" class="rounded-lg border p-4 space-y-2">
        <?php echo csrf_field(); ?>
        <h2 class="font-semibold">Update setting</h2>
        <input name="key" class="rounded-md border-gray-300" placeholder="key" required>
        <input name="value" class="rounded-md border-gray-300" placeholder="value" required>
        <button class="bg-blue-600 text-white px-3 py-1 rounded">Save</button>
    </form>
    <form method="POST" action="<?php echo e(route('admin.settings.update-api-key')); ?>" class="rounded-lg border p-4 space-y-2">
        <?php echo csrf_field(); ?>
        <h2 class="font-semibold">API key</h2>
        <input name="name" class="rounded-md border-gray-300" placeholder="name">
        <input name="key" class="rounded-md border-gray-300" placeholder="value">
        <button class="bg-blue-600 text-white px-3 py-1 rounded">Save key</button>
    </form>

    <div class="rounded-lg border border-gray-200 p-4">
        <h2 class="text-lg font-semibold text-gray-900">Global Settings</h2>
        <?php if(empty($settings)): ?>
            <p class="mt-2 text-sm text-gray-500">No global settings configured.</p>
        <?php else: ?>
            <dl class="mt-4 space-y-2">
                <?php $__currentLoopData = $settings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex justify-between text-sm">
                        <dt class="text-gray-600"><?php echo e($key); ?></dt>
                        <dd class="text-gray-900"><?php echo e(is_array($value) ? json_encode($value) : $value); ?></dd>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </dl>
        <?php endif; ?>
    </div>

    <div class="rounded-lg border border-gray-200 p-4">
        <h2 class="text-lg font-semibold text-gray-900">Feature Flags</h2>
        <?php if($featureFlags->isEmpty()): ?>
            <p class="mt-2 text-sm text-gray-500">No feature flags configured.</p>
        <?php else: ?>
            <ul class="mt-4 space-y-2">
                <?php $__currentLoopData = $featureFlags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $flag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="text-sm text-gray-600"><?php echo e($flag->name); ?> · <?php echo e($flag->enabled ? 'Enabled' : 'Disabled'); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\marketingmanager-laravel\resources\views/admin/settings/index.blade.php ENDPATH**/ ?>