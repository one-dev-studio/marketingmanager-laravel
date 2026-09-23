<?php $__env->startSection('page-title', 'Products'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex gap-6">
    <aside class="w-56 shrink-0">
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <h2 class="text-sm font-semibold text-gray-900 mb-3">Categories</h2>
            <a href="<?php echo e(route('main.products.index', ['organizationId' => $organizationId])); ?>" class="block text-sm mb-2 <?php echo e(empty($filters['category_id']) ? 'text-blue-700 font-medium' : 'text-gray-600'); ?>">All</a>
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('main.products.index', ['organizationId' => $organizationId, 'category_id' => $category->id])); ?>"
                   class="block text-sm mb-1 <?php echo e(($filters['category_id'] ?? null) == $category->id ? 'text-blue-700 font-medium' : 'text-gray-600'); ?>">
                    <?php echo e($category->name); ?>

                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </aside>
    <div class="flex-1 space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Products</h1>
                <p class="text-sm text-gray-600">Catalog with images, SKUs, and variants</p>
            </div>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Models\Product::class)): ?>
                <a href="<?php echo e(route('main.products.create', ['organizationId' => $organizationId])); ?>" class="bg-blue-600 text-white px-4 py-2 rounded-md">Add Product</a>
            <?php endif; ?>
        </div>
        <form class="flex gap-2" method="GET">
            <input name="search" value="<?php echo e($filters['search'] ?? ''); ?>" placeholder="Search name or SKU" class="rounded-md border-gray-300 flex-1">
            <button class="border px-3 rounded-md">Search</button>
        </form>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Models\Product::class)): ?>
        <form method="POST" action="<?php echo e(route('main.products.import', ['organizationId' => $organizationId])); ?>" enctype="multipart/form-data" class="bg-white border rounded-lg p-3 flex items-center gap-3">
            <?php echo csrf_field(); ?>
            <input type="file" name="file" accept=".csv,.xlsx,.xls" required class="text-sm">
            <label class="text-sm"><input type="checkbox" name="skip_duplicates" value="1" checked> Skip duplicates</label>
            <button class="text-sm text-blue-700">Import CSV/Excel</button>
        </form>
        <?php endif; ?>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="bg-white border rounded-lg overflow-hidden">
                    <?php if($product->image): ?>
                        <img src="<?php echo e(asset('storage/'.$product->image)); ?>" alt="" class="h-36 w-full object-cover">
                    <?php else: ?>
                        <div class="h-36 bg-gray-100"></div>
                    <?php endif; ?>
                    <div class="p-4">
                        <a href="<?php echo e(route('main.products.show', ['organizationId' => $organizationId, 'product' => $product])); ?>" class="font-medium text-gray-900"><?php echo e($product->name); ?></a>
                        <p class="text-sm text-gray-500"><?php echo e($product->sku); ?> · <?php echo e($product->category?->name); ?></p>
                        <p class="text-sm mt-1">$<?php echo e(number_format($product->price, 2)); ?> · stock <?php echo e($product->stock); ?></p>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-full p-8 text-center text-gray-500 bg-white border rounded-lg">No products yet.</div>
            <?php endif; ?>
        </div>
        <?php echo e($products->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\marketingmanager-laravel\resources\views/products/index.blade.php ENDPATH**/ ?>