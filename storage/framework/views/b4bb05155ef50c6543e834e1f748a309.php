

<?php $__env->startSection('title', 'Create Account'); ?>
<?php $__env->startSection('subtitle', 'Start your free trial'); ?>

<?php $__env->startSection('content'); ?>
<form method="POST" action="<?php echo e(route('register')); ?>" class="space-y-5">
    <?php echo csrf_field(); ?>

    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
        <input id="name" name="name" type="text" value="<?php echo e(old('name')); ?>" required autofocus
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    </div>

    <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input id="email" name="email" type="email" value="<?php echo e(old('email')); ?>" required
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    </div>

    <div>
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <input id="password" name="password" type="password" required
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    </div>

    <div>
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
        <input id="password_confirmation" name="password_confirmation" type="password" required
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    </div>

    <button type="submit" class="w-full rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">
        Create Account
    </button>
</form>

<p class="mt-6 text-center text-sm text-gray-600">
    Already have an account?
    <a href="<?php echo e(route('login')); ?>" class="text-indigo-600 hover:text-indigo-500">Sign in</a>
</p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\marketingmanager-laravel\resources\views/auth/register.blade.php ENDPATH**/ ?>