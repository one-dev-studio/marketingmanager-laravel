<?php $__env->startSection('page-title', 'Press Releases'); ?>
<?php $__env->startSection('content'); ?>
<div class="space-y-4">
    <h1 class="text-2xl font-semibold">Press releases</h1>
    <form method="POST" action="<?php echo e(route('main.press-releases.store', ['organizationId' => $organizationId])); ?>" class="bg-white border rounded-lg p-4 space-y-2">
        <?php echo csrf_field(); ?>
        <input name="title" required placeholder="Title" class="w-full rounded-md border-gray-300">
        <textarea name="content" required rows="4" class="w-full rounded-md border-gray-300" placeholder="Body"></textarea>
        <input type="datetime-local" name="release_date" class="rounded-md border-gray-300">
        <button class="bg-blue-600 text-white px-4 py-2 rounded-md">Create</button>
    </form>
    <div class="bg-white border rounded-lg">
        <?php $__empty_1 = true; $__currentLoopData = $pressReleases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $release): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="p-4 border-b">
                <p class="font-medium"><?php echo e($release->title); ?></p>
                <p class="text-sm text-gray-500"><?php echo e($release->status); ?> · <?php echo e($release->release_date); ?></p>
                <div class="flex gap-2 text-sm mt-1">
                    <form method="POST" action="<?php echo e(route('main.press-releases.schedule', ['organizationId' => $organizationId, 'pressRelease' => $release])); ?>"><?php echo csrf_field(); ?><button class="text-blue-700">Schedule</button></form>
                    <form method="POST" action="<?php echo e(route('main.press-releases.approve', ['organizationId' => $organizationId, 'pressRelease' => $release])); ?>"><?php echo csrf_field(); ?><button>Approve</button></form>
                    <form method="POST" action="<?php echo e(route('main.press-releases.distribute', ['organizationId' => $organizationId, 'pressRelease' => $release])); ?>"><?php echo csrf_field(); ?><button>Distribute</button></form>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="p-8 text-center text-gray-500">None yet.</div>
        <?php endif; ?>
    </div>
    <?php echo e($pressReleases->links()); ?>

    <div class="bg-white border rounded-lg p-4">
        <h2 class="font-medium mb-2">Media contacts</h2>
        <form method="POST" action="<?php echo e(route('main.press-releases.contacts.store', ['organizationId' => $organizationId])); ?>" class="grid md:grid-cols-4 gap-2">
            <?php echo csrf_field(); ?>
            <input name="name" required placeholder="Name" class="rounded-md border-gray-300">
            <input name="email" type="email" required placeholder="Email" class="rounded-md border-gray-300">
            <input name="media_outlet" placeholder="Outlet" class="rounded-md border-gray-300">
            <button class="text-blue-700">Add</button>
        </form>
        <form class="mt-3" method="POST" enctype="multipart/form-data" action="<?php echo e(route('main.press-releases.contacts.import', ['organizationId' => $organizationId])); ?>">
            <?php echo csrf_field(); ?>
            <input type="file" name="file">
            <button class="text-sm">Import</button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\marketingmanager-laravel\resources\views/press-releases/index.blade.php ENDPATH**/ ?>