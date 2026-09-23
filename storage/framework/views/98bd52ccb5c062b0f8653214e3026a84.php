<?php $__env->startSection('page-title', 'Profile'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6 max-w-2xl">
    <div>
        <h1 class="text-2xl font-semibold text-gray-900">Profile</h1>
        <p class="mt-1 text-sm text-gray-600">Update your name, email, and password.</p>
    </div>

    <?php if(session('success')): ?>
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('profile.update')); ?>" class="bg-white rounded-lg border border-gray-200 p-6 space-y-4">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <div>
            <label class="block text-sm font-medium text-gray-700" for="name">Name</label>
            <input id="name" name="name" value="<?php echo e(old('name', $user->name)); ?>" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700" for="email">Email</label>
            <input id="email" name="email" type="email" value="<?php echo e(old('email', $user->email)); ?>" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700" for="timezone">Timezone</label>
            <input id="timezone" name="timezone" value="<?php echo e(old('timezone', $user->timezone ?? 'UTC')); ?>" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>
        <button class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Save profile</button>
    </form>

    <form method="POST" action="<?php echo e(route('profile.password.update')); ?>" class="bg-white rounded-lg border border-gray-200 p-6 space-y-4">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <h2 class="font-medium text-gray-900">Password</h2>
        <div>
            <label class="block text-sm font-medium text-gray-700" for="current_password">Current password</label>
            <input id="current_password" name="current_password" type="password" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700" for="password">New password</label>
            <input id="password" name="password" type="password" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700" for="password_confirmation">Confirm password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>
        <button class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Update password</button>
    </form>

    <?php if(isset($sessions) && $sessions->count()): ?>
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h2 class="font-medium text-gray-900 mb-3">Active sessions</h2>
            <ul class="text-sm text-gray-600 space-y-1">
                <?php $__currentLoopData = $sessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($session->browser ?? 'Browser'); ?> · <?php echo e($session->ip_address); ?> · <?php echo e($session->last_activity); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\marketingmanager-laravel\resources\views/profile/show.blade.php ENDPATH**/ ?>