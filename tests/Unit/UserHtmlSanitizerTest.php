<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Services\Security\UserHtmlSanitizer;
use Tests\TestCase;

class UserHtmlSanitizerTest extends TestCase
{
    public function test_strips_script_tags_from_builder_like_html(): void
    {
        $sanitizer = new UserHtmlSanitizer;

        $dirty = '<p>Hello</p><script>alert(1)</script><img src=x onerror="alert(2)">';

        $clean = $sanitizer->sanitize($dirty);

        $this->assertStringContainsString('<p>Hello</p>', (string) $clean);
        $this->assertStringNotContainsString('<script', strtolower((string) $clean));
        $this->assertStringNotContainsString('onerror', strtolower((string) $clean));
    }

    public function test_returns_null_for_empty_input(): void
    {
        $sanitizer = new UserHtmlSanitizer;

        $this->assertNull($sanitizer->sanitize(null));
        $this->assertSame('', $sanitizer->sanitize(''));
    }
}
