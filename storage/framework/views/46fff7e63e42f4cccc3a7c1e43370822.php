<?php $__env->startSection('page-title', 'Billing'); ?>
<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <h1 class="text-2xl font-semibold">Billing</h1>
    <?php $__currentLoopData = $alerts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alert): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="rounded-md px-4 py-3 text-sm <?php echo e($alert['type']==='danger' ? 'bg-red-50 text-red-800' : ($alert['type']==='warning' ? 'bg-yellow-50 text-yellow-800' : 'bg-blue-50 text-blue-800')); ?>"><?php echo e($alert['message']); ?></div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <div class="bg-white border rounded-lg p-6">
        <h2 class="font-medium">Current plan</h2>
        <p class="text-sm text-gray-600"><?php echo e($subscription?->plan?->name ?? 'None'); ?> · <?php echo e($subscription?->status ?? 'inactive'); ?>

            <?php if($subscription?->trial_ends_at): ?> · trial until <?php echo e($subscription->trial_ends_at->toFormattedDateString()); ?> <?php endif; ?>
        </p>
        <p class="text-xs text-gray-400 mt-2">Gateways: Stripe <?php echo e($gateways['stripe'] ? 'configured' : 'needs STRIPE_SECRET'); ?> · PayPal <?php echo e($gateways['paypal'] ? 'configured' : 'needs PAYPAL_CLIENT_ID'); ?></p>
    </div>
    <div class="grid md:grid-cols-3 gap-4">
        <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <form method="POST" action="<?php echo e($subscription ? route('main.billing.subscription.upgrade', ['organizationId' => $organizationId]) : route('main.billing.subscription.create', ['organizationId' => $organizationId])); ?>" class="bg-white border rounded-lg p-4">
                <?php echo csrf_field(); ?>
                <?php if($subscription): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>
                <input type="hidden" name="plan_id" value="<?php echo e($plan->id); ?>">
                <h3 class="font-medium"><?php echo e($plan->name); ?></h3>
                <p class="text-sm">$<?php echo e($plan->price); ?> / <?php echo e($plan->billing_cycle); ?></p>
                <button class="mt-3 text-blue-700 text-sm"><?php echo e($subscription ? 'Switch' : 'Subscribe'); ?></button>
            </form>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <div class="bg-white border rounded-lg p-4">
        <h2 class="font-medium mb-2">Usage (<?php echo e($usageStats['period']); ?>)</h2>
        <p class="text-sm">AI tokens <?php echo e($usageStats['ai_usage']['total_tokens']); ?> · cost $<?php echo e($usageStats['ai_usage']['total_cost']); ?></p>
    </div>
    <div class="bg-white border rounded-lg overflow-hidden">
        <h2 class="font-medium p-4">Invoices</h2>
        <?php $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="px-4 py-3 border-t flex justify-between text-sm">
                <span><?php echo e($invoice->invoice_number); ?> · <?php echo e($invoice->status); ?></span>
                <span>$<?php echo e($invoice->total); ?> due <?php echo e($invoice->due_date); ?></span>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php echo e($invoices->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\marketingmanager-laravel\resources\views/organization/billing/index.blade.php ENDPATH**/ ?>