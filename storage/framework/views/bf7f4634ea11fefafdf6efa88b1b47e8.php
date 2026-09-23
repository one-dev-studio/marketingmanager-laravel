<?php $__env->startSection('page-title', 'Reports'); ?>
<?php $__env->startSection('content'); ?>
<div class="space-y-4">
    <div class="flex justify-between">
        <h1 class="text-2xl font-semibold">Reports</h1>
        <a href="<?php echo e(route('main.reports.create', ['organizationId' => $organizationId])); ?>" class="bg-blue-600 text-white px-4 py-2 rounded-md">Builder</a>
    </div>
    <div class="bg-white border rounded-lg">
        <?php $__empty_1 = true; $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="p-4 border-b flex justify-between">
                <a href="<?php echo e(route('main.reports.show', ['organizationId' => $organizationId, 'reportId' => $report->id])); ?>"><?php echo e($report->name); ?></a>
                <form method="POST" action="<?php echo e(route('main.reports.generate', ['organizationId' => $organizationId, 'reportId' => $report->id])); ?>"><?php echo csrf_field(); ?><button class="text-sm text-blue-700">Generate</button></form>
                <form method="POST" action="<?php echo e(route('main.reports.export', ['organizationId' => $organizationId, 'reportId' => $report->id])); ?>"><?php echo csrf_field(); ?><input type="hidden" name="format" value="pdf"><button class="text-sm">PDF</button></form>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="p-8 text-center text-gray-500">No reports.</div>
        <?php endif; ?>
    </div>
    <?php echo e($reports->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\marketingmanager-laravel\resources\views/reports/index.blade.php ENDPATH**/ ?>