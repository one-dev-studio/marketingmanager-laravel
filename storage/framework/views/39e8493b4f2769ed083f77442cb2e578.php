

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">System Logs</h1>
        <p class="mt-1 text-gray-600">View platform system logs and performance metrics</p>
    </div>

    <?php if(!empty($performanceMetrics)): ?>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <?php $__currentLoopData = $performanceMetrics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $metric => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="rounded-lg border border-gray-200 p-4">
                    <p class="text-sm text-gray-500"><?php echo e(ucwords(str_replace('_', ' ', $metric))); ?></p>
                    <p class="text-2xl font-semibold text-gray-900"><?php echo e(is_array($value) ? json_encode($value) : $value); ?></p>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>

    <div class="overflow-hidden rounded-lg border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Level</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Message</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="px-4 py-3 text-sm text-gray-600"><?php echo e($log->level ?? 'info'); ?></td>
                        <td class="px-4 py-3 text-sm text-gray-900"><?php echo e(\Illuminate\Support\Str::limit($log->message ?? '', 120)); ?></td>
                        <td class="px-4 py-3 text-sm text-gray-600"><?php echo e($log->created_at?->format('M j, Y H:i')); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="3" class="px-4 py-8 text-center text-gray-500">No system logs found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if(method_exists($logs, 'links')): ?>
        <?php echo e($logs->links()); ?>

    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\marketingmanager-laravel\resources\views/admin/logs/index.blade.php ENDPATH**/ ?>