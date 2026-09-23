<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <div class="flex justify-between">
        <div>
            <h1 class="text-2xl font-semibold">Clients</h1>
            <p class="text-sm text-gray-600">Organizations managed by this agency</p>
        </div>
        <a href="<?php echo e(route('agency.clients.create', $agency)); ?>" class="bg-blue-600 text-white px-4 py-2 rounded-md">Add New Client</a>
    </div>
    <form method="GET" class="flex gap-2">
        <input name="search" value="<?php echo e($filters['search'] ?? ''); ?>" class="rounded-md border-gray-300 flex-1" placeholder="Search">
        <button class="border px-3 py-2 rounded-md">Search</button>
    </form>
    <div class="bg-white border rounded-lg overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50"><tr>
                <th class="text-left p-3">Organization</th>
                <th class="text-left p-3">Users</th>
                <th class="p-3"></th>
            </tr></thead>
            <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="border-t">
                    <td class="p-3"><?php echo e($client->name); ?></td>
                    <td class="p-3"><?php echo e($client->users_count); ?></td>
                    <td class="p-3 text-right">
                        <a class="text-blue-700" href="<?php echo e(route('agency.clients.show', [$agency, $client->id])); ?>">View Organization</a>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="3" class="p-8 text-center text-gray-500">No clients yet.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php echo e($clients->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.agency', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\marketingmanager-laravel\resources\views/agency/clients/index.blade.php ENDPATH**/ ?>