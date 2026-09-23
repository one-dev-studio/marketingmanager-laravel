<?php $__env->startSection('page-title', 'Create Brand'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6 max-w-3xl">
    <div>
        <h1 class="text-2xl font-semibold text-gray-900">Create Brand</h1>
        <p class="mt-1 text-sm text-gray-600">Add guidelines and tone of voice used by AI content tools</p>
    </div>

    <form method="POST" action="<?php echo e(route('main.brands.store', ['organizationId' => $organizationId])); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php echo $__env->make('brands._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <div class="flex items-center gap-3">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Save brand</button>
            <a href="<?php echo e(route('main.brands.index', ['organizationId' => $organizationId])); ?>" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\marketingmanager-laravel\resources\views/brands/create.blade.php ENDPATH**/ ?>