<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e($survey->title); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-8">
<div class="max-w-xl mx-auto bg-white border rounded-lg p-6 space-y-4">
    <h1 class="text-2xl font-semibold"><?php echo e($survey->title); ?></h1>
    <p class="text-sm text-gray-600"><?php echo e($survey->description); ?></p>
    <?php if(session('success')): ?>
        <p class="text-green-700 text-sm"><?php echo e(session('success')); ?></p>
    <?php endif; ?>
    <form method="POST" action="<?php echo e(route('public.survey.submit', $survey)); ?>" class="space-y-4">
        <?php echo csrf_field(); ?>
        <input type="email" name="respondent_email" class="w-full border rounded px-3 py-2" placeholder="Email (optional)">
        <?php $__currentLoopData = $survey->questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div>
                <label class="block text-sm font-medium mb-1"><?php echo e($question->question); ?><?php if($question->is_required): ?> * <?php endif; ?></label>
                <input type="hidden" name="answers[<?php echo e($i); ?>][question_id]" value="<?php echo e($question->id); ?>">
                <?php if(in_array($question->type, ['textarea'])): ?>
                    <textarea name="answers[<?php echo e($i); ?>][response]" class="w-full border rounded px-3 py-2" <?php if($question->is_required): echo 'required'; endif; ?>></textarea>
                <?php elseif(in_array($question->type, ['radio','select']) && is_array($question->options)): ?>
                    <select name="answers[<?php echo e($i); ?>][response]" class="w-full border rounded px-3 py-2" <?php if($question->is_required): echo 'required'; endif; ?>>
                        <?php $__currentLoopData = $question->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($option); ?>"><?php echo e($option); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                <?php else: ?>
                    <input name="answers[<?php echo e($i); ?>][response]" class="w-full border rounded px-3 py-2" <?php if($question->is_required): echo 'required'; endif; ?>>
                <?php endif; ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <button class="bg-blue-600 text-white px-4 py-2 rounded-md">Submit</button>
    </form>
</div>
</body>
</html>
<?php /**PATH D:\projects\marketingmanager-laravel\resources\views/surveys/public.blade.php ENDPATH**/ ?>