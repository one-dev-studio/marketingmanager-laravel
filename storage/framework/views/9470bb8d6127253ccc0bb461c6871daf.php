<?php $__env->startSection('page-title', 'Contacts'); ?>
<?php $__env->startSection('content'); ?>
<div class="space-y-4">
    <div class="flex justify-between">
        <div>
            <h1 class="text-2xl font-semibold">Contacts</h1>
            <p class="text-sm text-gray-600">Search, lists, subscribe status</p>
        </div>
        <a href="<?php echo e(route('main.email-marketing.contacts.create', ['organizationId' => $organizationId])); ?>" class="bg-blue-600 text-white px-4 py-2 rounded-md">Add</a>
    </div>
    <form class="flex gap-2" method="GET">
        <input name="search" value="<?php echo e($filters['search'] ?? ''); ?>" class="rounded-md border-gray-300 flex-1" placeholder="Search">
        <select name="status" class="rounded-md border-gray-300">
            <option value="">Status</option>
            <?php $__currentLoopData = ['active','unsubscribed','bounced']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($status); ?>" <?php if(($filters['status'] ?? '') === $status): echo 'selected'; endif; ?>><?php echo e($status); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <select name="contact_list_id" class="rounded-md border-gray-300">
            <option value="">List</option>
            <?php $__currentLoopData = $lists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($list->id); ?>" <?php if(($filters['contact_list_id'] ?? '') == $list->id): echo 'selected'; endif; ?>><?php echo e($list->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <button class="border px-3 rounded-md">Filter</button>
    </form>
    <form method="POST" enctype="multipart/form-data" action="<?php echo e(route('main.email-marketing.contacts.import', ['organizationId' => $organizationId])); ?>" class="bg-white border rounded p-3 flex gap-3 items-center">
        <?php echo csrf_field(); ?>
        <input type="file" name="file" required>
        <button class="text-sm text-blue-700">Import CSV</button>
    </form>
    <div class="bg-white border rounded-lg overflow-hidden">
        <?php $__empty_1 = true; $__currentLoopData = $contacts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="p-4 border-b flex justify-between">
                <div>
                    <a class="font-medium" href="<?php echo e(route('main.email-marketing.contacts.show', ['organizationId' => $organizationId, 'contact' => $contact])); ?>"><?php echo e($contact->full_name); ?></a>
                    <p class="text-sm text-gray-500"><?php echo e($contact->email); ?> · <?php echo e($contact->status); ?> · <?php echo e($contact->contactLists->pluck('name')->join(', ')); ?></p>
                    <p class="text-xs text-gray-400"><?php echo e($contact->tags->pluck('tag')->join(', ')); ?></p>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="p-8 text-center text-gray-500">No contacts.</div>
        <?php endif; ?>
    </div>
    <?php echo e($contacts->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\marketingmanager-laravel\resources\views/contacts/index.blade.php ENDPATH**/ ?>