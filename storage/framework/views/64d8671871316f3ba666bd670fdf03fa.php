<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <h1 class="text-2xl font-bold">Platform billing</h1>
    <div class="grid md:grid-cols-3 gap-4">
        <div class="border rounded-lg p-4"><p class="text-sm text-gray-500">Subscriptions</p><p class="text-2xl font-semibold"><?php echo e($subscriptions->count()); ?></p></div>
        <div class="border rounded-lg p-4"><p class="text-sm text-gray-500">Open invoices</p><p class="text-2xl font-semibold"><?php echo e($openInvoices); ?></p></div>
        <div class="border rounded-lg p-4"><p class="text-sm text-gray-500">Paid (30d)</p><p class="text-2xl font-semibold"><?php echo e($paidTotal); ?></p></div>
    </div>
    <table class="min-w-full bg-white border text-sm">
        <thead class="bg-gray-50"><tr><th class="p-3 text-left">Org</th><th class="p-3 text-left">Plan</th><th class="p-3 text-left">Status</th></tr></thead>
        <tbody>
        <?php $__currentLoopData = $subscriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr class="border-t"><td class="p-3"><?php echo e($sub->organization?->name); ?></td><td class="p-3"><?php echo e($sub->plan?->name); ?></td><td class="p-3"><?php echo e($sub->status); ?></td></tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\marketingmanager-laravel\resources\views/admin/billing/index.blade.php ENDPATH**/ ?>