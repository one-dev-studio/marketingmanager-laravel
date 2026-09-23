<?php $__env->startSection('page-title', 'Sentiment'); ?>
<?php $__env->startSection('content'); ?>
<div class="space-y-4" x-data="{ text: '', output: '', alerts: [], async run() {
    const { data } = await axios.post(<?php echo json_encode(url('/main/'.$organizationId.'/analytics/sentiment'), 15, 512) ?>, { text: this.text });
    this.output = JSON.stringify(data.data || data, null, 2);
    const alerts = await axios.get(<?php echo json_encode(url('/main/'.$organizationId.'/analytics/sentiment/alerts'), 15, 512) ?>);
    this.alerts = alerts.data.data || [];
}}">
    <h1 class="text-2xl font-semibold">Sentiment</h1>
    <textarea x-model="text" rows="5" class="w-full rounded-md border-gray-300" placeholder="Text or paste a URL"></textarea>
    <button @click="run" class="bg-blue-600 text-white px-4 py-2 rounded-md">Analyze</button>
    <pre class="bg-gray-50 border rounded p-4 text-sm" x-text="output"></pre>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\marketingmanager-laravel\resources\views/intelligence/sentiment.blade.php ENDPATH**/ ?>