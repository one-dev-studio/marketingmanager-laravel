<?php $__env->startSection('page-title', 'Paid Ads'); ?>
<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <div class="flex justify-between">
        <div>
            <h1 class="text-2xl font-semibold">Paid Ad Campaigns</h1>
            <p class="text-sm text-gray-600">Budget, schedule, and performance</p>
        </div>
    </div>
    <form method="POST" action="<?php echo e(route('main.paid-campaigns.store', ['organizationId' => $organizationId])); ?>" class="bg-white border rounded-lg p-4 grid md:grid-cols-4 gap-3">
        <?php echo csrf_field(); ?>
        <input name="name" required class="rounded-md border-gray-300" placeholder="Campaign name">
        <select name="platform" class="rounded-md border-gray-300">
            <?php $__currentLoopData = ['facebook','instagram','google','linkedin','twitter','tiktok','pinterest']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($p); ?>"><?php echo e(ucfirst($p)); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <input name="budget" type="number" step="0.01" required class="rounded-md border-gray-300" placeholder="Budget">
        <select name="budget_type" class="rounded-md border-gray-300">
            <option value="daily">Daily</option>
            <option value="lifetime">Lifetime</option>
        </select>
        <input name="start_date" type="date" required class="rounded-md border-gray-300">
        <input name="end_date" type="date" class="rounded-md border-gray-300">
        <button class="bg-blue-600 text-white px-4 py-2 rounded-md">Create</button>
    </form>
    <div class="bg-white border rounded-lg divide-y">
        <?php $__empty_1 = true; $__currentLoopData = $paidCampaigns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campaign): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="p-4 flex justify-between">
                <div>
                    <h2 class="font-medium"><?php echo e($campaign->name); ?></h2>
                    <p class="text-sm text-gray-500"><?php echo e($campaign->platform); ?> · <?php echo e($campaign->status); ?> · <?php echo e($campaign->currency ?? 'USD'); ?> <?php echo e($campaign->budget); ?> (<?php echo e($campaign->budget_type); ?>)</p>
                </div>
                <p class="text-sm">Spent <?php echo e($campaign->spent ?? 0); ?></p>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="p-8 text-center text-gray-500">No paid campaigns yet.</div>
        <?php endif; ?>
    </div>
    <?php echo e($paidCampaigns->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\marketingmanager-laravel\resources\views/paid-ads/campaigns.blade.php ENDPATH**/ ?>