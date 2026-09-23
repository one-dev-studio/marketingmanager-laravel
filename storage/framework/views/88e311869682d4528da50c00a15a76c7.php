<?php $__env->startSection('page-title', 'Competitors'); ?>
<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <h1 class="text-2xl font-semibold">Competitor analysis</h1>
    <form method="POST" action="<?php echo e(route('main.competitors.store', ['organizationId' => $organizationId])); ?>" class="bg-white border rounded-lg p-4 grid md:grid-cols-3 gap-3">
        <?php echo csrf_field(); ?>
        <input name="name" required class="rounded-md border-gray-300" placeholder="Competitor name">
        <input name="website" class="rounded-md border-gray-300" placeholder="https://">
        <button class="bg-blue-600 text-white px-4 py-2 rounded-md">Add competitor</button>
    </form>
    <div class="bg-white border rounded-lg divide-y">
        <?php $__empty_1 = true; $__currentLoopData = $competitors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $competitor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="p-4 flex justify-between">
                <div>
                    <h2 class="font-medium"><?php echo e($competitor->name); ?></h2>
                    <p class="text-sm text-gray-500"><?php echo e($competitor->website); ?> · <?php echo e($competitor->analyses->count()); ?> analyses</p>
                </div>
                <form method="POST" action="<?php echo e(route('main.competitors.analysis', ['organizationId' => $organizationId, 'competitor' => $competitor])); ?>">
                    <?php echo csrf_field(); ?>
                    <button class="text-sm text-blue-700">Run analysis</button>
                </form>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="p-8 text-center text-gray-500">No competitors tracked.</div>
        <?php endif; ?>
    </div>
    <?php echo e($competitors->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\marketingmanager-laravel\resources\views/intelligence/competitors.blade.php ENDPATH**/ ?>