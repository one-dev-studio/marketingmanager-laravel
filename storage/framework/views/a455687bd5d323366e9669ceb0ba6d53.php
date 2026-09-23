

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Content Moderation</h1>
        <p class="mt-1 text-gray-600">Review flagged content and moderation queue</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="rounded-lg border border-gray-200 p-4">
            <p class="text-sm text-gray-500">Moderation Queue</p>
            <p class="text-2xl font-semibold text-gray-900"><?php echo e($moderationQueue->total()); ?></p>
        </div>
        <div class="rounded-lg border border-gray-200 p-4">
            <p class="text-sm text-gray-500">Pending Flags</p>
            <p class="text-2xl font-semibold text-gray-900"><?php echo e($contentFlags->total()); ?></p>
        </div>
    </div>

    <div class="rounded-lg border border-gray-200 p-4">
        <h2 class="text-lg font-semibold text-gray-900">Pending Flags</h2>
        <?php if($contentFlags->isEmpty()): ?>
            <p class="mt-2 text-sm text-gray-500">No pending content flags.</p>
        <?php else: ?>
            <ul class="mt-4 divide-y divide-gray-100">
                <?php $__currentLoopData = $contentFlags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $flag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="py-3 text-sm text-gray-600">
                        Flag #<?php echo e($flag->id); ?> · <?php echo e($flag->reason ?? 'No reason'); ?> · <?php echo e($flag->created_at?->diffForHumans()); ?>

                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\marketingmanager-laravel\resources\views/admin/content/index.blade.php ENDPATH**/ ?>