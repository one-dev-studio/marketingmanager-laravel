<?php $__env->startSection('page-title', 'Channels'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Channels</h1>
            <p class="mt-1 text-sm text-gray-600">Connect social, email, ads, and influencer destinations</p>
        </div>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Models\Channel::class)): ?>
            <a href="<?php echo e(route('main.social.channels.create', ['organizationId' => $organizationId])); ?>"
               class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Add Channel</a>
        <?php endif; ?>
    </div>

    <?php if(session('success')): ?>
        <div class="rounded-md bg-green-50 text-green-800 px-4 py-3 text-sm"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="rounded-md bg-red-50 text-red-800 px-4 py-3 text-sm"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <h2 class="text-sm font-medium text-gray-900 mb-3">OAuth connect</h2>
        <div class="flex flex-wrap gap-2">
            <?php $__currentLoopData = $oauthPlatforms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $platform): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('main.social.auth.redirect', ['organizationId' => $organizationId, 'platform' => $platform])); ?>"
                   class="border border-gray-300 px-3 py-1.5 rounded-md text-sm capitalize hover:bg-gray-50">
                    Connect <?php echo e($platform === 'twitter' ? 'X' : $platform); ?>

                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        <?php $__empty_1 = true; $__currentLoopData = $channels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $channel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $connected = $channel->socialConnection?->isConnected();
            ?>
            <div class="bg-white rounded-lg border border-gray-200 p-4 space-y-3">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="font-medium text-gray-900"><?php echo e($channel->display_name); ?></h3>
                        <p class="text-sm text-gray-500 capitalize"><?php echo e(str_replace('_', ' ', $channel->type)); ?>

                            <?php if($channel->platform): ?> · <?php echo e($channel->platform); ?> <?php endif; ?>
                        </p>
                    </div>
                    <span class="text-xs px-2 py-1 rounded-full <?php echo e($connected ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600'); ?>">
                        <?php echo e($connected ? 'Connected' : ($channel->status ?? 'disconnected')); ?>

                    </span>
                </div>
                <?php if($channel->type === 'influencer'): ?>
                    <p class="text-sm text-gray-600">
                        Followers: <?php echo e(number_format($channel->settings?->settings_json['follower_count'] ?? 0)); ?>

                        · Engagement: <?php echo e($channel->settings?->settings_json['engagement_rate'] ?? 0); ?>%
                    </p>
                <?php endif; ?>
                <div class="flex items-center gap-3 text-sm">
                    <form method="POST" action="<?php echo e(route('main.social.channels.test', ['organizationId' => $organizationId, 'channel' => $channel])); ?>">
                        <?php echo csrf_field(); ?>
                        <button class="text-blue-600 hover:text-blue-800">Test</button>
                    </form>
                    <a href="<?php echo e(route('main.social.channels.edit', ['organizationId' => $organizationId, 'channel' => $channel])); ?>" class="text-blue-600 hover:text-blue-800">Edit</a>
                    <?php if($channel->socialConnection): ?>
                        <form method="POST" action="<?php echo e(route('main.social.connections.destroy', ['organizationId' => $organizationId, 'connection' => $channel->socialConnection])); ?>">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button class="text-gray-600 hover:text-gray-800">Disconnect</button>
                        </form>
                    <?php endif; ?>
                    <form method="POST" action="<?php echo e(route('main.social.channels.destroy', ['organizationId' => $organizationId, 'channel' => $channel])); ?>"
                          onsubmit="return confirm('Delete this channel?');">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button class="text-red-600 hover:text-red-800">Delete</button>
                    </form>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-full bg-white rounded-lg border border-gray-200 p-8 text-center text-gray-500">No channels yet.</div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\marketingmanager-laravel\resources\views/channels/index.blade.php ENDPATH**/ ?>