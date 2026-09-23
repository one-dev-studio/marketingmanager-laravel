<?php $__env->startSection('page-title', 'Templates'); ?>
<?php $__env->startSection('content'); ?>
<div class="space-y-4">
    <div class="flex justify-between">
        <h1 class="text-2xl font-semibold">Email Templates</h1>
        <a href="<?php echo e(route('main.email-marketing.templates.builder', ['organizationId' => $organizationId])); ?>" class="bg-blue-600 text-white px-4 py-2 rounded-md">Builder</a>
    </div>
    <div class="bg-white border rounded-lg">
        <?php $__empty_1 = true; $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <a class="block p-4 border-b" href="<?php echo e(route('main.email-marketing.templates.builder', ['organizationId' => $organizationId, 'emailTemplate' => $template])); ?>"><?php echo e($template->name); ?> · <?php echo e($template->subject); ?></a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="p-8 text-center text-gray-500">No templates.</div>
        <?php endif; ?>
    </div>
    <?php echo e($templates->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\marketingmanager-laravel\resources\views/email/templates/index.blade.php ENDPATH**/ ?>