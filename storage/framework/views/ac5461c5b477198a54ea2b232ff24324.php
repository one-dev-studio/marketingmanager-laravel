<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Onboarding - MarketPulse</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <style>[x-cloak]{display:none!important}</style>
</head>
<body class="bg-gray-50 antialiased">
    <div class="min-h-screen bg-gradient-to-br from-indigo-50 to-indigo-100 flex items-center justify-center p-4">
        <div id="onboarding-app" class="w-full max-w-7xl mx-auto"></div>
    </div>
</body>
</html>
<?php /**PATH D:\projects\marketingmanager-laravel\resources\views/onboarding/index.blade.php ENDPATH**/ ?>