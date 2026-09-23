
<?php $__env->startSection('page-title', 'Landing Pages'); ?>
<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <div class="flex justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Landing Pages</h1>
            <p class="mt-1 text-sm text-gray-600">Build, preview, and publish</p>
        </div>
        <a href="<?php echo e(route('main.landing-pages.create', ['organizationId' => $organizationId])); ?>" class="bg-blue-600 text-white px-4 py-2 rounded-md">Create</a>
    </div>
    <div class="bg-white rounded-lg border overflow-hidden">
        <?php $__empty_1 = true; $__currentLoopData = $landingPages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="p-4 border-b flex items-center justify-between">
                <div>
                    <h2 class="font-medium"><?php echo e($page->name); ?></h2>
                    <p class="text-sm text-gray-500"><?php echo e($page->status); ?> · /<?php echo e($page->slug); ?></p>
                </div>
                <div class="flex gap-3 text-sm">
                    <a class="text-blue-700" href="<?php echo e(route('main.landing-pages.builder', ['organizationId' => $organizationId, 'landingPage' => $page])); ?>">Builder</a>
                    <a href="<?php echo e(route('main.landing-pages.preview', ['organizationId' => $organizationId, 'landingPage' => $page])); ?>">Preview</a>
                    <a href="<?php echo e(route('main.landing-pages.analytics', ['organizationId' => $organizationId, 'landingPage' => $page])); ?>">Analytics</a>
                    <form method="POST" action="<?php echo e(route('main.landing-pages.publish', ['organizationId' => $organizationId, 'landingPage' => $page])); ?>"><?php echo csrf_field(); ?><button class="text-blue-700">Publish</button></form>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="p-8 text-center text-gray-500">No landing pages yet.</div>
        <?php endif; ?>
    </div>
    <?php echo e($landingPages->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\marketingmanager-laravel\resources\views/landing-pages/index.blade.php ENDPATH**/ ?>