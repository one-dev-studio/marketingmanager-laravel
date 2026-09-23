
<?php $__env->startSection('page-title', 'Surveys'); ?>
<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <div class="flex justify-between">
        <div>
            <h1 class="text-2xl font-semibold">Surveys</h1>
            <p class="text-sm text-gray-600">Build, distribute, and analyze</p>
        </div>
        <a href="<?php echo e(route('main.surveys.create', ['organizationId' => $organizationId])); ?>" class="bg-blue-600 text-white px-4 py-2 rounded-md">Create</a>
    </div>
    <div class="bg-white border rounded-lg">
        <?php $__empty_1 = true; $__currentLoopData = $surveys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $survey): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="p-4 border-b flex justify-between">
                <div>
                    <h2 class="font-medium"><?php echo e($survey->title); ?></h2>
                    <p class="text-sm text-gray-500"><?php echo e($survey->status); ?> · <?php echo e($survey->response_count); ?> responses</p>
                </div>
                <div class="flex gap-3 text-sm">
                    <a class="text-blue-700" href="<?php echo e(route('main.surveys.builder', ['organizationId' => $organizationId, 'survey' => $survey])); ?>">Builder</a>
                    <a href="<?php echo e(route('main.surveys.analytics', ['organizationId' => $organizationId, 'survey' => $survey])); ?>">Analytics</a>
                    <a href="<?php echo e(route('public.survey', $survey)); ?>">Public link</a>
                    <a href="<?php echo e(route('main.surveys.export', ['organizationId' => $organizationId, 'survey' => $survey])); ?>">Export</a>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="p-8 text-center text-gray-500">No surveys yet.</div>
        <?php endif; ?>
    </div>
    <?php echo e($surveys->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\marketingmanager-laravel\resources\views/surveys/index.blade.php ENDPATH**/ ?>