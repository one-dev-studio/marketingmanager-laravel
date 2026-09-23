<?php $__env->startSection('page-title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div id="dashboard-app" data-organization-id="<?php echo e($organizationId); ?>">
    <dashboard-component></dashboard-component>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script type="module">
import { createApp } from 'vue';
import DashboardComponent from '/resources/js/components/DashboardComponent.vue';

const app = createApp({});
app.component('dashboard-component', DashboardComponent);
app.mount('#dashboard-app');
</script>
<?php $__env->stopPush(); ?>



<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\marketingmanager-laravel\resources\views/dashboard/index.blade.php ENDPATH**/ ?>