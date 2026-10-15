<?php

declare(strict_types=1);

namespace Tests\Concerns;

use Illuminate\Support\Facades\File;

/**
 * Seeds a minimal public/build manifest for feature tests that call withVite().
 *
 * PHPUnit runs without npm run build; the real manifest is gitignored under public/build.
 */
trait InteractsWithViteManifest
{
    private bool $viteBuildDirectoryExistedBeforeTest = false;

    protected function seedMinimalViteManifest(): void
    {
        $buildPath = public_path('build');
        $this->viteBuildDirectoryExistedBeforeTest = is_dir($buildPath);

        $assetsPath = $buildPath.'/assets';
        if (! is_dir($assetsPath)) {
            File::makeDirectory($assetsPath, 0755, true);
        }

        $manifest = [
            'resources/css/app.css' => [
                'file' => 'assets/app-test.css',
                'src' => 'resources/css/app.css',
                'isEntry' => true,
            ],
            'resources/js/inertia/app.tsx' => [
                'file' => 'assets/app-test.js',
                'src' => 'resources/js/inertia/app.tsx',
                'isEntry' => true,
            ],
        ];

        File::put(
            $buildPath.'/manifest.json',
            json_encode($manifest, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES)
        );
        File::put($assetsPath.'/app-test.css', '/* vite test fixture */');
        File::put($assetsPath.'/app-test.js', '// vite test fixture');
    }

    protected function tearDownInteractsWithViteManifest(): void
    {
        if ($this->viteBuildDirectoryExistedBeforeTest) {
            return;
        }

        $buildPath = public_path('build');
        if (is_dir($buildPath)) {
            File::deleteDirectory($buildPath);
        }
    }
}
