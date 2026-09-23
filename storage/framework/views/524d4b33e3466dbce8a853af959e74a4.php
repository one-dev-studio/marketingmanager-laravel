<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e($page->seo_settings['title'] ?? $page->name); ?></title>
    <meta name="description" content="<?php echo e($page->seo_settings['description'] ?? $page->description); ?>">
</head>
<body>
<?php echo $variant->html_content ?? $page->html_content; ?>

</body>
</html>
<?php /**PATH D:\projects\marketingmanager-laravel\resources\views/landing-pages/public.blade.php ENDPATH**/ ?>