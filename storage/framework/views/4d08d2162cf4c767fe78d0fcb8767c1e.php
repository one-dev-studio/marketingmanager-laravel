<?php $__env->startSection('page-title', 'Predictive'); ?>
<?php $__env->startSection('content'); ?>
<div class="space-y-4" x-data="{ campaignId: '', output: '', async forecast() {
    const base = <?php echo json_encode(url('/main/'.$organizationId.'/analytics/predictions'), 15, 512) ?>;
    const { data } = await axios.post(`${base}/campaigns/${this.campaignId}`);
    this.output = JSON.stringify(data.data || data, null, 2);
}}">
    <h1 class="text-2xl font-semibold">Predictive analytics</h1>
    <select x-model="campaignId" class="rounded-md border-gray-300">
        <option value="">Campaign</option>
        <?php $__currentLoopData = $campaigns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campaign): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($campaign->id); ?>"><?php echo e($campaign->name); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
    <button @click="forecast" class="bg-blue-600 text-white px-4 py-2 rounded-md">Forecast</button>
    <div class="flex gap-2 text-sm">
        <a class="text-blue-700" href="<?php echo e(url('/main/'.$organizationId.'/analytics/predictions/optimal-posting-times')); ?>">Posting times</a>
        <a class="text-blue-700" href="<?php echo e(url('/main/'.$organizationId.'/analytics/predictions/budget-optimization')); ?>">Budget</a>
    </div>
    <pre class="bg-gray-50 border rounded p-4 text-sm" x-text="output"></pre>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\marketingmanager-laravel\resources\views/intelligence/predictive.blade.php ENDPATH**/ ?>