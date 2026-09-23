<?php $__env->startSection('page-title', 'Projects'); ?>
<?php $__env->startSection('content'); ?>
<div class="space-y-4">
    <div class="flex justify-between">
        <h1 class="text-2xl font-semibold">Projects</h1>
        <form method="POST" action="<?php echo e(route('main.projects.store', ['organizationId' => $organizationId])); ?>" class="flex gap-2">
            <?php echo csrf_field(); ?>
            <input name="name" required placeholder="New project" class="rounded-md border-gray-300">
            <select name="status" class="rounded-md border-gray-300">
                <?php $__currentLoopData = ['planning','in_progress','review','completed']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($status); ?>"><?php echo e(str_replace('_',' ', $status)); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <button class="bg-blue-600 text-white px-3 rounded-md">Create</button>
        </form>
    </div>
    <?php if($templates->count()): ?>
        <div class="text-sm">Templates:
            <?php $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <form class="inline" method="POST" action="<?php echo e(route('main.projects.templates.create-project', ['organizationId' => $organizationId, 'projectTemplate' => $template])); ?>"><?php echo csrf_field(); ?><button class="text-blue-700"><?php echo e($template->name); ?></button></form>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
    <div class="grid md:grid-cols-2 gap-4">
        <?php $__empty_1 = true; $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white border rounded-lg p-4">
                <h2 class="font-medium"><?php echo e($project->name); ?></h2>
                <p class="text-sm text-gray-500 capitalize"><?php echo e(str_replace('_',' ', $project->status)); ?> · <?php echo e($project->progress); ?>%</p>
                <div class="h-2 bg-gray-100 rounded mt-2"><div class="h-2 bg-blue-600 rounded" style="width: <?php echo e($project->progress); ?>%"></div></div>
                <p class="text-xs text-gray-400 mt-2"><?php echo e($project->members->pluck('name')->join(', ')); ?> · <?php echo e($project->client?->name); ?></p>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-2 p-8 text-center text-gray-500 bg-white border rounded-lg">No projects.</div>
        <?php endif; ?>
    </div>
    <?php echo e($projects->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\marketingmanager-laravel\resources\views/projects/index.blade.php ENDPATH**/ ?>