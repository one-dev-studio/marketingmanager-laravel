<?php $__env->startSection('page-title', 'Content Ideation'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <h1 class="text-2xl font-semibold">Content Ideation</h1>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php $__currentLoopData = [
            ['SEO Analysis', 'tools.seo-analysis', 'Technical and content report for a URL'],
            ['Email Template', 'tools.email-template', 'Generate a branded email'],
            ['Label Inspiration', 'tools.label-inspiration', 'Name and tagline variations'],
            ['Image Generator', 'tools.image-generator', 'Prompt-based image generation'],
            ['Product Catalog', 'tools.product-catalog', 'Catalog copy from products'],
            ['Blog Post', 'tools.blog', 'Long-form blog draft'],
            ['Press Release', 'tools.press-release', 'AI press release draft'],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tool): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('main.'.$tool[1], ['organizationId' => $organizationId])); ?>" class="bg-white border rounded-lg p-4 hover:border-blue-400">
                <h2 class="font-medium"><?php echo e($tool[0]); ?></h2>
                <p class="text-sm text-gray-500 mt-1"><?php echo e($tool[2]); ?></p>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\marketingmanager-laravel\resources\views/tools/index.blade.php ENDPATH**/ ?>