<?php $__env->startSection('page-title', 'Create Campaign'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto space-y-6" x-data="{ currentStep: 0, steps: ['Plan', 'Content', 'Review'] }">
    <div class="flex items-center justify-between max-w-2xl mx-auto mb-4">
        <template x-for="(step, index) in steps" :key="index">
            <div class="flex items-center flex-1">
                <div class="flex flex-col items-center flex-1">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-semibold"
                         :class="currentStep === index ? 'bg-blue-600 text-white' : (currentStep > index ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-600')"
                         x-text="currentStep > index ? '✓' : index + 1"></div>
                    <span class="mt-2 text-sm" x-text="step"></span>
                </div>
            </div>
        </template>
    </div>

    <form method="POST" action="<?php echo e(route('main.campaigns.store', ['organizationId' => $organizationId])); ?>" class="bg-white border rounded-lg p-6 space-y-4">
        <?php echo csrf_field(); ?>
        <div x-show="currentStep === 0" class="space-y-4">
            <h2 class="text-xl font-semibold">Plan your campaign</h2>
            <div>
                <label class="block text-sm font-medium text-gray-700">Campaign name</label>
                <input name="name" required class="mt-1 w-full rounded-md border-gray-300" placeholder="Summer launch">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Campaign goal</label>
                <textarea name="description" rows="3" class="mt-1 w-full rounded-md border-gray-300" placeholder="What do you want to achieve?"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Goal type</label>
                <select name="goal_type" class="mt-1 w-full rounded-md border-gray-300">
                    <option value="product_launch">Product Launch</option>
                    <option value="brand_awareness">Brand Awareness</option>
                    <option value="lead_generation">Lead Generation</option>
                    <option value="event_promotion">Event Promotion</option>
                    <option value="custom">Custom</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Brand</label>
                <select name="brand_id" class="mt-1 w-full rounded-md border-gray-300">
                    <option value="">Select brand</option>
                    <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($brand->id); ?>" <?php if((string) $brandId === (string) $brand->id): echo 'selected'; endif; ?>><?php echo e($brand->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Channels</label>
                <div class="grid grid-cols-2 gap-2">
                    <?php $__empty_1 = true; $__currentLoopData = $channels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $channel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="channels[<?php echo e($i); ?>][id]" value="<?php echo e($channel->id); ?>">
                            <?php echo e($channel->display_name); ?>

                        </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-sm text-gray-500">No active channels. Add a channel first.</p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Start date</label>
                    <input type="date" name="start_date" required value="<?php echo e(now()->addDay()->toDateString()); ?>" class="mt-1 w-full rounded-md border-gray-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">End date</label>
                    <input type="date" name="end_date" value="<?php echo e(now()->addMonths(1)->toDateString()); ?>" class="mt-1 w-full rounded-md border-gray-300">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Budget</label>
                <input type="number" name="budget" min="0" step="0.01" value="0" required class="mt-1 w-full rounded-md border-gray-300">
            </div>
            <button type="button" class="bg-blue-600 text-white px-4 py-2 rounded-md" @click="currentStep = 1">Continue to content</button>
        </div>

        <div x-show="currentStep === 1" x-cloak class="space-y-4">
            <h2 class="text-xl font-semibold">Content</h2>
            <p class="text-sm text-gray-600">Save the campaign plan now. You can generate channel content from the campaign page next.</p>
            <div class="flex gap-2">
                <button type="button" class="border px-4 py-2 rounded-md" @click="currentStep = 0">Back</button>
                <button type="button" class="bg-blue-600 text-white px-4 py-2 rounded-md" @click="currentStep = 2">Continue to review</button>
            </div>
        </div>

        <div x-show="currentStep === 2" x-cloak class="space-y-4">
            <h2 class="text-xl font-semibold">Review &amp; create</h2>
            <p class="text-sm text-gray-600">Create the campaign as a draft, then submit content for review from the campaign list.</p>
            <div class="flex gap-2">
                <button type="button" class="border px-4 py-2 rounded-md" @click="currentStep = 1">Back</button>
                <button class="bg-blue-600 text-white px-4 py-2 rounded-md">Create campaign</button>
            </div>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\marketingmanager-laravel\resources\views/campaigns/create.blade.php ENDPATH**/ ?>