<?php
    $isEdit = isset($brand);
    $keywordsValue = old('keywords', $isEdit ? implode(', ', $brand->keywords ?? []) : '');
    $avoidValue = old('avoid_keywords', $isEdit ? implode(', ', $brand->avoid_keywords ?? []) : '');
?>

<div class="space-y-6"
     x-data="brandForm({
        generateUrl: <?php echo \Illuminate\Support\Js::from(route('main.brands.generate-concept', ['organizationId' => $organizationId]))->toHtml() ?>,
        csrf: <?php echo \Illuminate\Support\Js::from(csrf_token())->toHtml() ?>,
     })">
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Models\Brand::class)): ?>
        <div class="bg-white rounded-lg border border-gray-200 p-6 space-y-4">
            <label class="flex items-center gap-2 text-sm font-medium text-gray-900">
                <input type="checkbox" x-model="aiEnabled" class="rounded border-gray-300 text-blue-600">
                AI-generated concept brand
            </label>
            <div x-show="aiEnabled" x-cloak class="space-y-3">
                <p class="text-sm text-gray-600">Generate a draft summary, audience, guidelines, and keywords. You can edit everything before saving.</p>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Industry or category</label>
                    <input type="text" x-model="industry" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <button type="button"
                        @click="generateConcept()"
                        :disabled="generating"
                        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 disabled:opacity-50">
                    <span x-text="generating ? 'Generating…' : 'Generate concept'"></span>
                </button>
                <p x-show="error" x-text="error" class="text-sm text-red-600"></p>
            </div>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-lg border border-gray-200 p-6 space-y-4">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Brand name</label>
            <input id="name" name="name" type="text" required maxlength="255"
                   x-model="name"
                   value="<?php echo e(old('name', $isEdit ? $brand->name : ($prefillName ?? ''))); ?>"
                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
            <label for="summary" class="block text-sm font-medium text-gray-700 mb-1">Summary</label>
            <textarea id="summary" name="summary" rows="3" maxlength="1000"
                      x-model="summary"
                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"><?php echo e(old('summary', $isEdit ? $brand->summary : '')); ?></textarea>
            <?php $__errorArgs = ['summary'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
            <label for="guidelines" class="block text-sm font-medium text-gray-700 mb-1">Guidelines</label>
            <textarea id="guidelines" name="guidelines" rows="5"
                      x-model="guidelines"
                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"><?php echo e(old('guidelines', $isEdit ? $brand->guidelines : '')); ?></textarea>
            <?php $__errorArgs = ['guidelines'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
            <label for="tone_of_voice" class="block text-sm font-medium text-gray-700 mb-1">Tone of voice</label>
            <input id="tone_of_voice" name="tone_of_voice" type="text" maxlength="255"
                   x-model="tone_of_voice"
                   value="<?php echo e(old('tone_of_voice', $isEdit ? $brand->tone_of_voice : '')); ?>"
                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            <?php $__errorArgs = ['tone_of_voice'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
            <label for="audience" class="block text-sm font-medium text-gray-700 mb-1">Target audience</label>
            <textarea id="audience" name="audience" rows="3"
                      x-model="audience"
                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"><?php echo e(old('audience', $isEdit ? $brand->audience : '')); ?></textarea>
            <?php $__errorArgs = ['audience'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
            <label for="keywords" class="block text-sm font-medium text-gray-700 mb-1">Keywords to use</label>
            <input id="keywords" name="keywords" type="text"
                   x-model="keywords"
                   value="<?php echo e($keywordsValue); ?>"
                   placeholder="Comma-separated"
                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            <?php $__errorArgs = ['keywords'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
            <label for="avoid_keywords" class="block text-sm font-medium text-gray-700 mb-1">Keywords to avoid</label>
            <input id="avoid_keywords" name="avoid_keywords" type="text"
                   x-model="avoid_keywords"
                   value="<?php echo e($avoidValue); ?>"
                   placeholder="Comma-separated"
                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            <?php $__errorArgs = ['avoid_keywords'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
            <label for="business_model" class="block text-sm font-medium text-gray-700 mb-1">Business model</label>
            <input id="business_model" name="business_model" type="text"
                   value="<?php echo e(old('business_model', $isEdit ? $brand->business_model : '')); ?>"
                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div>
            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select id="status" name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <?php $status = old('status', $isEdit ? $brand->status : 'active'); ?>
                <option value="active" <?php if($status === 'active'): echo 'selected'; endif; ?>>Active</option>
                <option value="inactive" <?php if($status === 'inactive'): echo 'selected'; endif; ?>>Inactive</option>
            </select>
        </div>

        <div>
            <label for="logo" class="block text-sm font-medium text-gray-700 mb-1">Logo</label>
            <input id="logo" name="logo" type="file" accept="image/*" class="w-full text-sm text-gray-600">
            <?php $__errorArgs = ['logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    </div>
</div>

<style>[x-cloak] { display: none !important; }</style>
<script>
function brandForm(config) {
    return {
        aiEnabled: false,
        generating: false,
        error: '',
        industry: '',
        name: <?php echo \Illuminate\Support\Js::from(old('name', $isEdit ? $brand->name : ($prefillName ?? '')))->toHtml() ?>,
        summary: <?php echo \Illuminate\Support\Js::from(old('summary', $isEdit ? $brand->summary : ''))->toHtml() ?>,
        guidelines: <?php echo \Illuminate\Support\Js::from(old('guidelines', $isEdit ? $brand->guidelines : ''))->toHtml() ?>,
        tone_of_voice: <?php echo \Illuminate\Support\Js::from(old('tone_of_voice', $isEdit ? $brand->tone_of_voice : ''))->toHtml() ?>,
        audience: <?php echo \Illuminate\Support\Js::from(old('audience', $isEdit ? $brand->audience : ''))->toHtml() ?>,
        keywords: <?php echo \Illuminate\Support\Js::from($keywordsValue)->toHtml() ?>,
        avoid_keywords: <?php echo \Illuminate\Support\Js::from($avoidValue)->toHtml() ?>,
        async generateConcept() {
            this.generating = true;
            this.error = '';
            try {
                const keywords = this.keywords
                    .split(',')
                    .map((k) => k.trim())
                    .filter(Boolean);
                const response = await fetch(config.generateUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': config.csrf,
                    },
                    body: JSON.stringify({
                        name: this.name,
                        industry: this.industry,
                        keywords,
                    }),
                });
                const payload = await response.json();
                if (!response.ok || !payload.data) {
                    throw new Error(payload.message || payload.error || 'Could not generate a concept.');
                }
                const data = payload.data;
                this.summary = data.summary || this.summary;
                this.audience = data.audience || this.audience;
                this.guidelines = data.guidelines || this.guidelines;
                this.tone_of_voice = data.tone_of_voice || this.tone_of_voice;
                if (Array.isArray(data.keywords) && data.keywords.length) {
                    this.keywords = data.keywords.join(', ');
                }
                if (Array.isArray(data.avoid_keywords) && data.avoid_keywords.length) {
                    this.avoid_keywords = data.avoid_keywords.join(', ');
                }
            } catch (e) {
                this.error = e.message || 'Could not generate a concept.';
            } finally {
                this.generating = false;
            }
        },
    };
}
</script>
<?php /**PATH D:\projects\marketingmanager-laravel\resources\views/brands/_form.blade.php ENDPATH**/ ?>