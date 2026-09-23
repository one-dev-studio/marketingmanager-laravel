

<?php $__env->startSection('title', 'Admin Sign In'); ?>
<?php $__env->startSection('subtitle', 'Platform administrator access'); ?>

<?php $__env->startSection('content'); ?>
<form method="POST" action="<?php echo e(route('admin.login')); ?>" class="space-y-5">
    <?php echo csrf_field(); ?>

    <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Admin Email</label>
        <input id="email" name="email" type="email" value="<?php echo e(old('email')); ?>" required autofocus
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    </div>

    <div>
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <input id="password" name="password" type="password" required
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    </div>

    <div class="flex items-center">
        <label class="flex items-center text-sm text-gray-600">
            <input type="checkbox" name="remember" class="rounded border-gray-300 text-indigo-600">
            <span class="ml-2">Remember me</span>
        </label>
    </div>

    <button type="submit" class="w-full rounded-md bg-gray-900 px-4 py-2 text-white hover:bg-gray-800">
        Admin Sign In
    </button>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\marketingmanager-laravel\resources\views/admin/auth/login.blade.php ENDPATH**/ ?>