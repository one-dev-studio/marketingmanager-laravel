<?php $__env->startSection('page-title', 'Team'); ?>
<?php $__env->startSection('content'); ?>
<div class="space-y-4">
    <h1 class="text-2xl font-semibold">Team</h1>
    <form method="POST" action="<?php echo e(route('main.team.invite', ['organizationId' => $organizationId])); ?>" class="bg-white border rounded-lg p-4 flex gap-2">
        <?php echo csrf_field(); ?>
        <input name="email" type="email" required placeholder="Invite email" class="flex-1 rounded-md border-gray-300">
        <select name="role_id" class="rounded-md border-gray-300">
            <?php $__currentLoopData = $availableRoles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($role->id); ?>"><?php echo e($role->name === 'viewer' || $role->name === 'client' ? 'Client' : 'Org Admin'); ?> (<?php echo e($role->name); ?>)</option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <button class="bg-blue-600 text-white px-4 rounded-md">Invite</button>
    </form>
    <div class="bg-white border rounded-lg">
        <?php $__currentLoopData = $teamMembers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="p-4 border-b flex justify-between items-center">
                <div>
                    <p class="font-medium"><?php echo e($member->name); ?></p>
                    <p class="text-sm text-gray-500"><?php echo e($member->email); ?></p>
                </div>
                <div class="flex gap-2">
                    <form method="POST" action="<?php echo e(route('main.team.members.update-role', ['organizationId' => $organizationId, 'userId' => $member->id])); ?>">
                        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                        <select name="role_id" onchange="this.form.submit()" class="rounded-md border-gray-300 text-sm">
                            <?php $__currentLoopData = $availableRoles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($role->id); ?>" <?php if($member->pivot->role_id == $role->id): echo 'selected'; endif; ?>><?php echo e($role->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </form>
                    <form method="POST" action="<?php echo e(route('main.team.members.remove', ['organizationId' => $organizationId, 'userId' => $member->id])); ?>" onsubmit="return confirm('Remove member?');">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button class="text-red-600 text-sm">Remove</button>
                    </form>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\marketingmanager-laravel\resources\views/organization/team/index.blade.php ENDPATH**/ ?>