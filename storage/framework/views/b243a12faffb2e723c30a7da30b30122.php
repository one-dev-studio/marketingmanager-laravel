

<?php $__env->startSection('page-title', 'Campaigns'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Campaigns</h1>
            <p class="mt-1 text-sm text-gray-600">Manage your marketing campaigns</p>
        </div>
        <a href="<?php echo e(route('main.campaigns.create', ['organizationId' => $organizationId])); ?>"
           class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
            Create Campaign
        </a>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <?php $__empty_1 = true; $__currentLoopData = $campaigns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campaign): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="p-4 border-b border-gray-100 flex items-center justify-between hover:bg-gray-50">
                <div>
                    <h2 class="font-medium text-gray-900"><?php echo e($campaign->name); ?></h2>
                    <p class="text-sm text-gray-500"><?php echo e($campaign->status); ?> · <?php echo e($campaign->brand?->name ?? 'No brand'); ?></p>
                </div>
                <a href="<?php echo e(route('main.campaigns.show', ['organizationId' => $organizationId, 'campaign' => $campaign])); ?>"
                   class="text-blue-600 hover:text-blue-800 text-sm">View</a>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="p-8 text-center text-gray-500">No campaigns yet.</div>
        <?php endif; ?>
    </div>

    <?php echo e($campaigns->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\marketingmanager-laravel\resources\views/campaigns/index.blade.php ENDPATH**/ ?>