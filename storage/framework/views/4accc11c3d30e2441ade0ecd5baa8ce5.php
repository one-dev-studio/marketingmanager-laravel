<?php $__env->startSection('page-title', 'Email Campaigns'); ?>
<?php $__env->startSection('content'); ?>
<div class="space-y-4">
    <div class="flex justify-between">
        <h1 class="text-2xl font-semibold">Email Campaigns</h1>
        <div class="flex gap-2">
            <a href="<?php echo e(route('main.email-marketing.templates.index', ['organizationId' => $organizationId])); ?>" class="border px-4 py-2 rounded-md">Templates</a>
            <a href="<?php echo e(route('main.email-marketing.campaigns.create', ['organizationId' => $organizationId])); ?>" class="bg-blue-600 text-white px-4 py-2 rounded-md">Create</a>
        </div>
    </div>
    <div class="bg-white border rounded-lg overflow-hidden">
        <?php $__empty_1 = true; $__currentLoopData = $campaigns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campaign): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="p-4 border-b flex justify-between">
                <div>
                    <a class="font-medium" href="<?php echo e(route('main.email-marketing.campaigns.show', ['organizationId' => $organizationId, 'emailCampaign' => $campaign])); ?>"><?php echo e($campaign->name); ?></a>
                    <p class="text-sm text-gray-500"><?php echo e($campaign->subject); ?> · <?php echo e($campaign->status); ?> · <?php echo e(optional($campaign->scheduled_at)->toDayDateTimeString() ?? $campaign->sent_at); ?></p>
                    <p class="text-xs text-gray-400">Opens <?php echo e($campaign->opened_count); ?> · Clicks <?php echo e($campaign->clicked_count); ?></p>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="p-8 text-center text-gray-500">No campaigns.</div>
        <?php endif; ?>
    </div>
    <?php echo e($campaigns->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\marketingmanager-laravel\resources\views/email/campaigns/index.blade.php ENDPATH**/ ?>