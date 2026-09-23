<?php $__env->startSection('page-title', 'Reviews'); ?>
<?php $__env->startSection('content'); ?>
<div class="space-y-4">
    <h1 class="text-2xl font-semibold">Reputation inbox</h1>
    <div class="grid md:grid-cols-4 gap-3">
        <div class="bg-white border rounded p-3"><p class="text-xs text-gray-500">Avg rating</p><p class="text-xl"><?php echo e($aggregation['average_rating'] ?? $aggregation['avg'] ?? '—'); ?></p></div>
        <div class="bg-white border rounded p-3 col-span-3 text-sm">Sources: <?php echo e(is_array($sources) || $sources instanceof \Illuminate\Support\Collection ? collect($sources)->pluck('name')->join(', ') : ''); ?></div>
    </div>
    <form method="POST" action="<?php echo e(route('main.reviews.import', ['organizationId' => $organizationId])); ?>" class="bg-white border rounded p-4 text-sm space-y-2">
        <?php echo csrf_field(); ?>
        <input name="source_slug" placeholder="google" class="rounded-md border-gray-300">
        <textarea name="reviews_json" placeholder='[{"author":"Ada","rating":5,"content":"Great"}]' class="w-full rounded-md border-gray-300"></textarea>
        <button class="text-blue-700">Import JSON reviews</button>
    </form>
    <div class="bg-white border rounded-lg">
        <?php $__empty_1 = true; $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="p-4 border-b">
                <p class="font-medium"><?php echo e($review->author); ?> · <?php echo e($review->rating); ?>/5 · <?php echo e($review->platform); ?> · <?php echo e($review->sentiment); ?></p>
                <p class="text-sm text-gray-600"><?php echo e($review->content); ?></p>
                <form method="POST" action="<?php echo e(route('main.reviews.responses.store', ['organizationId' => $organizationId, 'review' => $review])); ?>" class="mt-2 flex gap-2">
                    <?php echo csrf_field(); ?>
                    <input name="response" required class="flex-1 rounded-md border-gray-300" placeholder="Reply">
                    <input type="hidden" name="response_type" value="public">
                    <button class="text-blue-700 text-sm">Respond</button>
                </form>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="p-8 text-center text-gray-500">No reviews.</div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\marketingmanager-laravel\resources\views/reviews/index.blade.php ENDPATH**/ ?>