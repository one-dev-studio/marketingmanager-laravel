<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Support\Facades\File;
use Tests\TestCase;

class InertiaLazyPagesTest extends TestCase
{
    public function test_inertia_entry_uses_lazy_page_glob(): void
    {
        $contents = File::get(resource_path('js/inertia/app.tsx'));

        $this->assertStringContainsString('const inertiaPages = import.meta.glob', $contents);
        $this->assertDoesNotMatchRegularExpression(
            '/import\.meta\.glob\([^)]*eager:\s*true/',
            $contents,
            'Inertia pages should not be eagerly bundled',
        );
    }
}
