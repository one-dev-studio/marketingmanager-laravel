<?php $__env->startSection('page-title', 'Tasks'); ?>
<?php $__env->startSection('content'); ?>
<div id="task-kanban-app">
    <task-kanban organization-id="<?php echo e($organizationId); ?>" :members='<?php echo json_encode($members, 15, 512) ?>'></task-kanban>
    <?php if($templates->count()): ?>
        <div class="mt-6 bg-white border rounded-lg p-4">
            <h2 class="font-medium mb-2">Templates</h2>
            <?php $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <form class="inline-block mr-2" method="POST" action="<?php echo e(route('main.tasks.templates.create-task', ['organizationId' => $organizationId, 'taskTemplate' => $template])); ?>">
                    <?php echo csrf_field(); ?>
                    <button class="text-sm text-blue-700">Use <?php echo e($template->name); ?></button>
                </form>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\marketingmanager-laravel\resources\views/tasks/index.blade.php ENDPATH**/ ?>