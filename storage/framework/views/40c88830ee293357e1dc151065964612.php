<?php $__env->startSection('content'); ?>
<section class="py-16">
    <div class="container" style="max-width: 48rem;">
        <h1 class="section-title" style="text-align:left;margin-bottom:1.5rem;"><?php echo e($heading); ?></h1>
        <div class="text-gray-700" style="display:flex;flex-direction:column;gap:1rem;">
            <?php echo $body; ?>

        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\marketingmanager-laravel\resources\views/public/legal.blade.php ENDPATH**/ ?>