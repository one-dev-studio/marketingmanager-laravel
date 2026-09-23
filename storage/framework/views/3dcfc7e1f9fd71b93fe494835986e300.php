<?php $__env->startSection('page-title', 'Organizations'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-semibold text-gray-900">Your organizations</h1>
        <p class="mt-1 text-sm text-gray-600">Select an organization to continue, or complete onboarding to create one.</p>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <?php $__empty_1 = true; $__currentLoopData = $organizations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $organization): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <a href="<?php echo e(route('main.dashboard', ['organizationId' => $organization->id])); ?>"
               class="block p-4 border-b border-gray-100 hover:bg-gray-50">
                <p class="font-medium text-gray-900"><?php echo e($organization->name); ?></p>
                <p class="text-sm text-gray-500"><?php echo e($organization->status); ?></p>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="p-8 text-center text-gray-500">
                <p>You don’t belong to an organization yet.</p>
                <a href="<?php echo e(route('main.onboarding')); ?>" class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Start onboarding</a>
            </div>
        <?php endif; ?>
    </div>

    <?php if($organizations->isNotEmpty()): ?>
        <a href="<?php echo e(route('main.onboarding')); ?>" class="text-sm text-blue-600 hover:text-blue-800">Run setup wizard</a>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\marketingmanager-laravel\resources\views/organizations/index.blade.php ENDPATH**/ ?>