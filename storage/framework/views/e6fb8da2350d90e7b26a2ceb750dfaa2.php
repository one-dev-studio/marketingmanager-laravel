<?php $__env->startSection('page-title', 'Collaboration'); ?>

<?php $__env->startSection('content'); ?>
<div id="collaboration-app" data-organization-id="<?php echo e($organizationId); ?>">
    <collaboration-component :organization-id="'<?php echo e($organizationId); ?>'"></collaboration-component>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script type="module">
import { createApp } from 'vue';
import CollaborationComponent from '/resources/js/components/CollaborationComponent.vue';

const app = createApp({});
app.component('collaboration-component', CollaborationComponent);
app.mount('#collaboration-app');
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\marketingmanager-laravel\resources\views/collaboration/index.blade.php ENDPATH**/ ?>