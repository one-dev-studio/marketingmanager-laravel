<?php $__env->startSection('page-title', 'Template Builder'); ?>
<?php $__env->startSection('content'); ?>
<div id="email-builder-app"
     data-organization-id="<?php echo e($organizationId); ?>"
     data-template="<?php echo e(json_encode($template)); ?>">
    <email-template-builder
        organization-id="<?php echo e($organizationId); ?>"
        :template="<?php echo e(json_encode($template)); ?>"
    ></email-template-builder>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>
<script type="module">
import { createApp } from 'vue';
import EmailTemplateBuilder from '/resources/js/components/EmailTemplateBuilder.vue';
createApp({}).component('email-template-builder', EmailTemplateBuilder).mount('#email-builder-app');
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\marketingmanager-laravel\resources\views/email/templates/builder.blade.php ENDPATH**/ ?>