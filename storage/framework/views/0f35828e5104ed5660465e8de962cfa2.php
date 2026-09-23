<?php $__env->startSection('page-title', 'Settings'); ?>
<?php $__env->startSection('content'); ?>
<div x-data="{ tab: 'general' }" class="space-y-4">
    <h1 class="text-2xl font-semibold">Organization Settings</h1>
    <div class="flex gap-2">
        <?php $__currentLoopData = ['general','integrations','notifications','security']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <button class="px-3 py-1 rounded border" :class="tab==='<?php echo e($tab); ?>' ? 'bg-blue-600 text-white' : ''" @click="tab='<?php echo e($tab); ?>'"><?php echo e(ucfirst($tab)); ?></button>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <form method="POST" action="<?php echo e(route('main.settings.update', ['organizationId' => $organizationId])); ?>" class="bg-white border rounded-lg p-6 space-y-3">
        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
        <div x-show="tab==='general'">
            <input name="name" value="<?php echo e($organization->name); ?>" class="w-full rounded-md border-gray-300">
            <input name="timezone" value="<?php echo e($organization->timezone); ?>" class="w-full rounded-md border-gray-300 mt-2" placeholder="Timezone">
            <input name="locale" value="<?php echo e($organization->locale); ?>" class="w-full rounded-md border-gray-300 mt-2">
        </div>
        <div x-show="tab==='integrations'" x-cloak>
            <input name="settings[slack_webhook]" value="<?php echo e($settings['slack_webhook'] ?? ''); ?>" class="w-full rounded-md border-gray-300" placeholder="Slack webhook">
        </div>
        <div x-show="tab==='notifications'" x-cloak>
            <label class="block text-sm"><input type="checkbox" name="settings[notify_email]" value="1" <?php if(!empty($settings['notify_email'])): echo 'checked'; endif; ?>> Email notifications</label>
            <label class="block text-sm"><input type="checkbox" name="settings[notify_in_app]" value="1" <?php if(!empty($settings['notify_in_app'])): echo 'checked'; endif; ?>> In-app</label>
            <label class="block text-sm"><input type="checkbox" name="settings[notify_push]" value="1" <?php if(!empty($settings['notify_push'])): echo 'checked'; endif; ?>> Push</label>
        </div>
        <div x-show="tab==='security'" x-cloak>
            <label class="block text-sm"><input type="checkbox" name="settings[require_2fa]" value="1" <?php if(!empty($settings['require_2fa'])): echo 'checked'; endif; ?>> Require 2FA</label>
        </div>
        <button class="bg-blue-600 text-white px-4 py-2 rounded-md">Save</button>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\marketingmanager-laravel\resources\views/organization/settings/index.blade.php ENDPATH**/ ?>