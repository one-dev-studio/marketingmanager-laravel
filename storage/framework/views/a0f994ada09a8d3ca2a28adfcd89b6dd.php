<?php $__env->startSection('page-title', 'Builder'); ?>
<?php $__env->startSection('content'); ?>
<div id="lp-builder">
    <landing-page-builder organization-id="<?php echo e($organizationId); ?>" :page='<?php echo json_encode($page, 15, 512) ?>'></landing-page-builder>
    <form class="mt-4" method="POST" action="<?php echo e(route('main.landing-pages.variants.store', ['organizationId' => $organizationId, 'landingPage' => $page])); ?>">
        <?php echo csrf_field(); ?>
        <input name="name" placeholder="Variant name" class="rounded-md border-gray-300">
        <input name="traffic_percentage" type="number" value="50" class="rounded-md border-gray-300 w-24">
        <button class="text-sm text-blue-700">Add variant split</button>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>
<script type="module">
import { createApp } from 'vue';
import LandingPageBuilder from '/resources/js/components/LandingPageBuilder.vue';
createApp({}).component('landing-page-builder', LandingPageBuilder).mount('#lp-builder');
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\marketingmanager-laravel\resources\views/landing-pages/builder.blade.php ENDPATH**/ ?>